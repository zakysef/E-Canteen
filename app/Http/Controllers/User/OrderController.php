<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function catalog(Request $request)
    {
        $sellers = User::where('role', 'admin')->where('is_active', true)->get();

        $menus = Menu::with('seller')
            ->where('status', 'tersedia')
            ->when($request->seller, fn($q) => $q->where('seller_id', $request->seller))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%"))
            ->get();

        return view('user.order.catalog', compact('menus', 'sellers'));
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'items'             => ['required', 'array', 'min:1'],
            'items.*.menu_id'   => ['required', 'exists:menus,id'],
            'items.*.qty'       => ['required', 'integer', 'min:1', 'max:10'],
            'waktu_pengambilan' => ['required', 'in:istirahat_1,istirahat_2'],
            'catatan'           => ['nullable', 'string', 'max:255'],
        ]);

        // Kelompokkan per seller
        $menuIds  = collect($data['items'])->pluck('menu_id');
        $menus    = Menu::whereIn('id', $menuIds)->where('status', 'tersedia')->get()->keyBy('id');

        if ($menus->count() !== $menuIds->unique()->count()) {
            return back()->with('error', 'Beberapa menu tidak tersedia.');
        }

        $sellers = $menus->pluck('seller_id')->unique();
        if ($sellers->count() > 1) {
            return back()->with('error', 'Pesanan hanya bisa dari satu penjual sekaligus.');
        }

        $sellerId   = $sellers->first();
        $totalHarga = 0;
        $itemList   = [];

        foreach ($data['items'] as $item) {
            $menu = $menus[$item['menu_id']];
            $subtotal = $menu->harga * $item['qty'];
            $totalHarga += $subtotal;
            $itemList[] = [
                'menu'     => $menu,
                'qty'      => $item['qty'],
                'subtotal' => $subtotal,
            ];
        }

        return view('user.order.checkout', compact('itemList', 'totalHarga', 'sellerId', 'data'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'seller_id'         => ['required', 'exists:users,id'],
            'items'             => ['required', 'array'],
            'items.*.menu_id'   => ['required', 'exists:menus,id'],
            'items.*.qty'       => ['required', 'integer', 'min:1'],
            'waktu_pengambilan' => ['required', 'in:istirahat_1,istirahat_2'],
            'catatan'           => ['nullable', 'string', 'max:255'],
        ]);

        $user   = auth()->user();
        $menus  = Menu::whereIn('id', collect($data['items'])->pluck('menu_id'))
            ->where('status', 'tersedia')->get()->keyBy('id');

        $totalHarga = collect($data['items'])->sum(fn($i) => $menus[$i['menu_id']]->harga * $i['qty']);

        if ($user->saldo < $totalHarga) {
            return back()->with('error', 'Saldo tidak mencukupi. Silakan top up terlebih dahulu.');
        }

        DB::transaction(function () use ($user, $data, $menus, $totalHarga) {
            $order = Order::create([
                'user_id'           => $user->id,
                'seller_id'         => $data['seller_id'],
                'total_harga'       => $totalHarga,
                'status'            => 'paid',
                'waktu_pengambilan' => $data['waktu_pengambilan'],
                'catatan'           => $data['catatan'] ?? null,
                'paid_at'           => now(),
            ]);

            foreach ($data['items'] as $item) {
                $menu = $menus[$item['menu_id']];
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_id'      => $menu->id,
                    'nama_menu'    => $menu->nama,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $menu->harga,
                    'subtotal'     => $menu->harga * $item['qty'],
                ]);
            }

            $user->kurangiSaldo($totalHarga, 'debit', "Pembayaran order #{$order->kode_order}", $order);
        });

        return redirect()->route('user.orders')->with('success', 'Pesanan berhasil! Saldo telah dipotong.');
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $tab = $request->get('tab', 'all');

        $orders = Order::with(['items', 'seller'])
            ->where('user_id', auth()->id())
            ->when($tab === 'aktif',     fn($q) => $q->whereIn('status', ['paid', 'preparing', 'ready']))
            ->when($tab === 'selesai',   fn($q) => $q->where('status', 'completed'))
            ->when($tab === 'batal',     fn($q) => $q->where('status', 'cancelled'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('user.order.index', compact('orders', 'tab'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('user.order.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status !== 'paid') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            auth()->user()->tambahSaldo(
                $order->total_harga,
                "Refund order #{$order->kode_order}",
                $order,
                'refund'
            );
        });

        return back()->with('success', 'Pesanan dibatalkan. Saldo dikembalikan.');
    }
}
