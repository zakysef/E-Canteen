<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $active_orders = Order::with(['items.menu', 'seller'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['paid', 'preparing', 'ready'])
            ->latest()
            ->get();

        $recent_transactions = Transaction::where('user_id', $user->id)
            ->latest()->take(5)->get();

        // Tampilkan menu dari semua penjual aktif (sample untuk dashboard)
        $featured_menus = Menu::with('seller')
            ->where('status', 'tersedia')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('user.dashboard', compact('user', 'active_orders', 'recent_transactions', 'featured_menus'));
    }
}
