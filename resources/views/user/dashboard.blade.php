@extends('layouts.user')

@section('title', 'Dashboard')

@section('content-inner')
<div class="max-w-4xl mx-auto">
    {{-- Greeting --}}
    <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-6 text-white mb-6">
        <p class="text-sm text-orange-100 mb-1">Selamat datang,</p>
        <h2 class="text-xl font-bold">{{ $user->name }} 👋</h2>
        @if($user->kelas)<p class="text-sm text-orange-100 mt-0.5">{{ $user->kelas }}</p>@endif
        <div class="mt-4 bg-white/20 rounded-xl px-4 py-3 inline-block">
            <p class="text-xs text-orange-100">Saldo Anda</p>
            <p class="text-2xl font-bold">Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <a href="{{ route('user.catalog') }}" class="bg-white border border-gray-100 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl mb-2">🛒</div>
            <p class="text-sm font-medium text-gray-700">Pre-Order</p>
        </a>
        <a href="{{ route('user.orders') }}" class="bg-white border border-gray-100 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl mb-2">📦</div>
            <p class="text-sm font-medium text-gray-700">Pesanan Saya</p>
        </a>
        <a href="{{ route('user.saldo.topup') }}" class="bg-white border border-gray-100 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl mb-2">💳</div>
            <p class="text-sm font-medium text-gray-700">Top Up Saldo</p>
        </a>
    </div>

    {{-- Pesanan Aktif --}}
    @if($active_orders->count() > 0)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Pesanan Aktif</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($active_orders as $order)
            <div class="px-5 py-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-sm text-gray-800">{{ $order->kode_order }}</p>
                    <p class="text-xs text-gray-500">{{ $order->seller->nama_toko ?? $order->seller->name }} · {{ $order->waktu_pengambilan === 'istirahat_1' ? 'Istirahat 1' : 'Istirahat 2' }}</p>
                    <p class="text-xs text-gray-400">{{ $order->items->count() }} item · Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    {{ $order->status === 'ready' ? 'bg-green-100 text-green-700 ring-2 ring-green-300' :
                       ($order->status === 'preparing' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ $order->status_label }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Featured Menu --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Menu Tersedia</h3>
            <a href="{{ route('user.catalog') }}" class="text-xs text-orange-500 hover:underline">Lihat semua →</a>
        </div>
        <div class="grid sm:grid-cols-3 gap-4 p-4">
            @forelse($featured_menus as $menu)
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <div class="h-28 bg-orange-50 flex items-center justify-center overflow-hidden">
                    @if($menu->foto)
                        <img src="{{ $menu->foto_url }}" alt="{{ $menu->nama }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl">{{ $menu->kategori === 'minuman' ? '🥤' : ($menu->kategori === 'snack' ? '🍿' : '🍱') }}</span>
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-sm font-medium text-gray-800">{{ $menu->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $menu->seller->nama_toko ?? $menu->seller->name }}</p>
                    <p class="text-sm font-bold text-orange-600 mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <p class="col-span-3 text-center text-gray-400 py-6">Belum ada menu tersedia.</p>
            @endforelse
        </div>
    </div>

    {{-- Transaksi Terakhir --}}
    @if($recent_transactions->count() > 0)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Transaksi Terakhir</h3>
            <a href="{{ route('user.saldo') }}" class="text-xs text-orange-500 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($recent_transactions as $tx)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $tx->tipe_label }}</p>
                    <p class="text-xs text-gray-400">{{ $tx->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="font-semibold text-sm {{ in_array($tx->tipe, ['topup', 'refund']) ? 'text-green-600' : 'text-red-500' }}">
                    {{ in_array($tx->tipe, ['topup', 'refund']) ? '+' : '-' }}Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
