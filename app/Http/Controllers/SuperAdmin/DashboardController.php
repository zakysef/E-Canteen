<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TopupRequest;
use App\Models\User;
use App\Models\WithdrawalRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::where('role', 'user')->count(),
            'total_sellers'    => User::where('role', 'admin')->count(),
            'pending_topup'    => TopupRequest::where('status', 'pending')->count(),
            'pending_withdrawal' => WithdrawalRequest::where('status', 'pending')->count(),
            'total_orders_today' => Order::whereDate('created_at', today())->count(),
            'pendapatan_today'   => Order::whereDate('created_at', today())
                ->whereIn('status', ['completed', 'ready', 'preparing', 'paid'])
                ->sum('total_harga'),
        ];

        $recent_topups    = TopupRequest::with('user')->latest()->take(5)->get();
        $recent_withdrawals = WithdrawalRequest::with('seller')->latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recent_topups', 'recent_withdrawals'));
    }
}
