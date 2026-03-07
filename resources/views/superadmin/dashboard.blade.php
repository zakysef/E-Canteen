@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name . '. Berikut ringkasan aktivitas terkini.')

@section('content-inner')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- Total Users --}}
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Pengguna</p>
                <p class="mt-1.5 text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                <p class="mt-1 text-xs text-gray-400">Siswa &amp; Guru terdaftar</p>
            </div>
            <div class="stat-icon bg-pink-100 text-pink-600">
                <i class="ph ph-users-three text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Total Penjual --}}
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Penjual</p>
                <p class="mt-1.5 text-3xl font-bold text-gray-800">{{ number_format($stats['total_sellers']) }}</p>
                <p class="mt-1 text-xs text-gray-400">Penjual terdaftar</p>
            </div>
            <div class="stat-icon bg-orange-100 text-orange-500">
                <i class="ph ph-storefront text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Top-Up Pending --}}
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Top-Up Pending</p>
                <p class="mt-1.5 text-3xl font-bold text-gray-800">{{ number_format($stats['pending_topup']) }}</p>
                <p class="mt-1 text-xs text-gray-400">Menunggu konfirmasi</p>
            </div>
            <div class="stat-icon bg-yellow-100 text-yellow-600">
                <i class="ph ph-coins text-xl"></i>
            </div>
        </div>
        @if($stats['pending_topup'] > 0)
        <div class="mt-3 pt-3 border-t border-pink-100">
            <a href="{{ route('superadmin.topup.index', ['status' => 'pending']) }}"
               class="text-xs font-medium text-pink-600 hover:text-pink-700 flex items-center gap-1">
                <i class="ph ph-arrow-right text-sm"></i>
                Tinjau sekarang
            </a>
        </div>
        @endif
    </div>

    {{-- Pencairan Pending --}}
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pencairan Pending</p>
                <p class="mt-1.5 text-3xl font-bold text-gray-800">{{ number_format($stats['pending_withdrawal']) }}</p>
                <p class="mt-1 text-xs text-gray-400">Menunggu konfirmasi</p>
            </div>
            <div class="stat-icon bg-blue-100 text-blue-600">
                <i class="ph ph-money text-xl"></i>
            </div>
        </div>
        @if($stats['pending_withdrawal'] > 0)
        <div class="mt-3 pt-3 border-t border-pink-100">
            <a href="{{ route('superadmin.withdrawal.index', ['status' => 'pending']) }}"
               class="text-xs font-medium text-pink-600 hover:text-pink-700 flex items-center gap-1">
                <i class="ph ph-arrow-right text-sm"></i>
                Tinjau sekarang
            </a>
        </div>
        @endif
    </div>

</div>

{{-- Recent Activity Tables --}}
<div class="grid lg:grid-cols-2 gap-5">

    {{-- Recent Top-Up Requests --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-pink-100">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="ph ph-coins text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800">Permintaan Top-Up Terbaru</h3>
            </div>
            <a href="{{ route('superadmin.topup.index') }}"
               class="text-xs font-medium text-pink-600 hover:text-pink-700 flex items-center gap-1">
                Lihat semua
                <i class="ph ph-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="divide-y divide-pink-50">
            @forelse($recent_topups as $topup)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-pink-50/40 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center font-semibold text-xs shrink-0">
                        {{ strtoupper(substr($topup->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $topup->user->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">
                            <span class="font-medium text-gray-600">Rp {{ number_format($topup->jumlah, 0, ',', '.') }}</span>
                            &middot; {{ $topup->metode }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0 ml-3">
                    <p class="text-xs text-gray-400 hidden sm:block">{{ $topup->created_at->format('d M') }}</p>
                    @php
                        $badgeClass = match($topup->status) {
                            'pending'  => 'bg-yellow-100 text-yellow-700',
                            'approved' => 'bg-blue-100 text-blue-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            default    => 'bg-gray-100 text-gray-600',
                        };
                        $badgeLabel = match($topup->status) {
                            'pending'  => 'Pending',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default    => ucfirst($topup->status),
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                <i class="ph ph-coins text-3xl mb-2 opacity-40"></i>
                <p class="text-sm">Belum ada permintaan top-up.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Withdrawal Requests --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-pink-100">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="ph ph-money text-sm"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800">Permintaan Pencairan Terbaru</h3>
            </div>
            <a href="{{ route('superadmin.withdrawal.index') }}"
               class="text-xs font-medium text-pink-600 hover:text-pink-700 flex items-center gap-1">
                Lihat semua
                <i class="ph ph-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="divide-y divide-pink-50">
            @forelse($recent_withdrawals as $withdrawal)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-pink-50/40 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-semibold text-xs shrink-0">
                        {{ strtoupper(substr($withdrawal->seller->name ?? 'S', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ $withdrawal->seller->nama_toko ?? $withdrawal->seller->name ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            <span class="font-medium text-gray-600">Rp {{ number_format($withdrawal->jumlah, 0, ',', '.') }}</span>
                            &middot; {{ $withdrawal->metode ?? $withdrawal->metode_pembayaran ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0 ml-3">
                    <p class="text-xs text-gray-400 hidden sm:block">{{ $withdrawal->created_at->format('d M') }}</p>
                    @php
                        $wBadgeClass = match($withdrawal->status) {
                            'pending'     => 'bg-yellow-100 text-yellow-700',
                            'approved'    => 'bg-blue-100 text-blue-700',
                            'transferred' => 'bg-green-100 text-green-700',
                            'completed'   => 'bg-green-100 text-green-700',
                            'rejected'    => 'bg-red-100 text-red-700',
                            default       => 'bg-gray-100 text-gray-600',
                        };
                        $wBadgeLabel = match($withdrawal->status) {
                            'pending'     => 'Pending',
                            'approved'    => 'Disetujui',
                            'transferred' => 'Ditransfer',
                            'completed'   => 'Selesai',
                            'rejected'    => 'Ditolak',
                            default       => ucfirst($withdrawal->status),
                        };
                    @endphp
                    <span class="badge {{ $wBadgeClass }}">{{ $wBadgeLabel }}</span>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                <i class="ph ph-money text-3xl mb-2 opacity-40"></i>
                <p class="text-sm">Belum ada permintaan pencairan.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
