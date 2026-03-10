<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = auth()->user();

        $stats = [
            'saldo'               => $seller->saldo,
            'pesanan_aktif'       => Order::where('seller_id', $seller->id)->whereIn('status', ['paid', 'preparing'])->count(),
            'menu_tersedia'       => Menu::where('seller_id', $seller->id)->where('status', 'tersedia')->where('stok', '>', 0)->count(),
            'pendapatan_hari_ini' => Order::where('seller_id', $seller->id)
                ->whereDate('created_at', today())
                ->whereIn('status', ['completed', 'ready', 'preparing', 'paid'])
                ->sum('total_harga'),
        ];

        $antrian = Order::with(['user', 'items'])
            ->where('seller_id', $seller->id)
            ->whereIn('status', ['paid', 'preparing'])
            ->orderBy('created_at')
            ->get();

        $recent_transactions = Transaction::where('user_id', $seller->id)
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'antrian', 'recent_transactions'));
    }
}
