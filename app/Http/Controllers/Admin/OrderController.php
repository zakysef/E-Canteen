<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])
            ->where('seller_id', auth()->id())
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->waktu, fn($q) => $q->where('waktu_pengambilan', $request->waktu))
            ->when($request->tanggal, fn($q) => $q->whereDate('created_at', $request->tanggal))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $currentStatus = $request->status ?? '';
        $currentWaktu  = $request->waktu ?? '';
        return view('admin.order.index', compact('orders', 'currentStatus', 'currentWaktu'));
    }

    public function show(Order $order)
    {
        abort_unless($order->seller_id === auth()->id(), 403);

        return view('admin.order.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        abort_unless($order->seller_id === auth()->id(), 403);

        $request->validate(['status' => ['required', 'in:preparing,ready,completed']]);

        $update = ['status' => $request->status];

        if ($request->status === 'ready') {
            $update['ready_at'] = now();
        }

        if ($request->status === 'completed') {
            $update['completed_at'] = now();

            // Credit seller balance with 'income' type
            DB::transaction(function () use ($order, $update) {
                $order->update($update);
                $seller = auth()->user();
                $seller->tambahSaldo(
                    $order->total_harga,
                    "Pendapatan order #{$order->kode_order}",
                    $order,
                    'income'
                );
            });

            return back()->with('success', "Order #{$order->kode_order} selesai. Saldo bertambah Rp " . number_format($order->total_harga, 0, ',', '.'));
        }

        $order->update($update);

        return back()->with('success', "Status order diperbarui menjadi {$order->fresh()->status_label}.");
    }

    public function laporan(Request $request)
    {
        $tanggal = $request->tanggal ?? today()->toDateString();

        $orders = Order::with(['user', 'items'])
            ->where('seller_id', auth()->id())
            ->whereDate('created_at', $tanggal)
            ->whereIn('status', ['ready', 'completed'])
            ->get();

        $total_pendapatan = $orders->sum('total_harga');
        $total_porsi      = $orders->sum(fn($o) => $o->items->sum('qty'));

        $per_menu = $orders->flatMap->items
            ->groupBy('nama_menu')
            ->map(fn($items) => [
                'qty'      => $items->sum('qty'),
                'subtotal' => $items->sum('subtotal'),
            ]);

        return view('admin.laporan', compact('orders', 'tanggal', 'total_pendapatan', 'total_porsi', 'per_menu'));
    }
}
