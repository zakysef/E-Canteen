@extends('layouts.user')

@section('title', 'Dashboard')

@section('content-inner')
<div class="max-w-4xl mx-auto">
    {{-- Greeting --}}
    <div class="bg-gradient-to-br from-rose-500 via-pink-500 to-pink-400 rounded-2xl p-6 text-white mb-6 relative overflow-hidden">
        {{-- Decorative circle --}}
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute -right-2 top-12 w-16 h-16 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-pink-100 mb-0.5">Selamat datang,</p>
                <h2 class="text-xl font-bold flex items-center gap-2">
                    {{ $user->name }}
                    <i class="ph ph-hand-waving text-pink-200"></i>
                </h2>
                @if($user->kelas)<p class="text-sm text-pink-100 mt-0.5">{{ $user->kelas }}</p>@endif
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <i class="ph ph-user text-white text-xl"></i>
            </div>
        </div>
        <div class="mt-4 bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 inline-flex items-center gap-3">
            <i class="ph ph-wallet text-pink-100 text-xl"></i>
            <div>
                <p class="text-xs text-pink-100">Saldo Anda</p>
                <p class="text-2xl font-bold leading-tight">Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        <a href="{{ route('user.catalog') }}" class="card p-4 text-center hover:border-pink-200 hover:shadow-md transition-all group">
            <div class="w-11 h-11 bg-gradient-to-br from-rose-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-2.5 shadow-sm shadow-pink-200 group-hover:scale-105 transition-transform">
                <i class="ph ph-storefront text-white text-xl"></i>
            </div>
            <p class="text-sm font-semibold text-gray-700">Pre-Order</p>
        </a>
        <a href="{{ route('user.orders') }}" class="card p-4 text-center hover:border-pink-200 hover:shadow-md transition-all group">
            <div class="w-11 h-11 bg-gradient-to-br from-pink-500 to-rose-400 rounded-xl flex items-center justify-center mx-auto mb-2.5 shadow-sm shadow-pink-200 group-hover:scale-105 transition-transform">
                <i class="ph ph-receipt text-white text-xl"></i>
            </div>
            <p class="text-sm font-semibold text-gray-700">Pesanan Saya</p>
        </a>
        <a href="{{ route('user.saldo.topup') }}" class="card p-4 text-center hover:border-pink-200 hover:shadow-md transition-all group">
            <div class="w-11 h-11 bg-gradient-to-br from-rose-400 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-2.5 shadow-sm shadow-pink-200 group-hover:scale-105 transition-transform">
                <i class="ph ph-credit-card text-white text-xl"></i>
            </div>
            <p class="text-sm font-semibold text-gray-700">Top Up Saldo</p>
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
            <a href="{{ route('user.catalog') }}" class="text-xs text-pink-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="grid sm:grid-cols-3 gap-4 p-4">
            @forelse($featured_menus as $menu)
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <div class="h-28 bg-gradient-to-br from-pink-50 to-rose-50 flex items-center justify-center overflow-hidden">
                    @if($menu->foto)
                        <img src="{{ $menu->foto_url }}" alt="{{ $menu->nama }}" class="w-full h-full object-cover">
                    @else
                        <i class="{{ $menu->kategori === 'minuman' ? 'ph ph-cup' : ($menu->kategori === 'snack' ? 'ph ph-cookie' : 'ph ph-bowl-food') }} text-4xl text-rose-300"></i>
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-sm font-medium text-gray-800">{{ $menu->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $menu->seller->nama_toko ?? $menu->seller->name }}</p>
                    <p class="text-sm font-bold text-rose-600 mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
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
            <a href="{{ route('user.saldo') }}" class="text-xs text-pink-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($recent_transactions as $tx)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $tx->tipe_label }}</p>
                    <p class="text-xs text-gray-400">{{ $tx->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="font-semibold text-sm {{ in_array($tx->tipe, ['topup', 'refund']) ? 'text-rose-600' : 'text-red-500' }}">
                    {{ in_array($tx->tipe, ['topup', 'refund']) ? '+' : '-' }}Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
