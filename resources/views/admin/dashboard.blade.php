@extends('layouts.admin')

@section('title', 'Dashboard Penjual')
@section('page-title', 'Dashboard Penjual')

@section('content-inner')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    {{-- Total Pendapatan Hari Ini --}}
    <div class="stat-card">
        <div class="stat-icon bg-green-100 text-green-600">
            <i class="ph ph-chart-bar"></i>
        </div>
        <div class="min-w-0">
            <p class="text-xs text-gray-500 font-medium mb-0.5">Pendapatan Hari Ini</p>
            <p class="text-lg font-bold text-green-700 truncate">Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Pesanan Aktif --}}
    <div class="stat-card">
        <div class="stat-icon bg-orange-100 text-orange-600">
            <i class="ph ph-receipt"></i>
        </div>
        <div class="min-w-0">
            <p class="text-xs text-gray-500 font-medium mb-0.5">Pesanan Aktif</p>
            <p class="text-lg font-bold text-orange-700">{{ $stats['pesanan_aktif'] }}</p>
        </div>
    </div>

    {{-- Menu Tersedia --}}
    <div class="stat-card">
        <div class="stat-icon bg-pink-100 text-pink-600">
            <i class="ph ph-fork-knife"></i>
        </div>
        <div class="min-w-0">
            <p class="text-xs text-gray-500 font-medium mb-0.5">Menu Tersedia</p>
            <p class="text-lg font-bold text-pink-700">{{ $stats['menu_tersedia'] }}</p>
        </div>
    </div>

    {{-- Saldo Virtual --}}
    <div class="stat-card">
        <div class="stat-icon bg-blue-100 text-blue-600">
            <i class="ph ph-wallet"></i>
        </div>
        <div class="min-w-0">
            <p class="text-xs text-gray-500 font-medium mb-0.5">Saldo Virtual</p>
            <p class="text-lg font-bold text-blue-700 truncate">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
        </div>
    </div>

</div>

{{-- Antrian Pesanan Aktif --}}
<div class="card mb-8">
    <div class="px-6 py-4 border-b border-pink-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="ph ph-clock text-orange-500 text-lg"></i>
            <h3 class="font-semibold text-gray-800">Antrian Pesanan Aktif</h3>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-orange-600 hover:underline font-medium">
            Lihat semua
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="data-table w-full">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pemesan</th>
                    <th>Item Pesanan</th>
                    <th>Total</th>
                    <th>Waktu Ambil</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($antrian as $order)
                <tr>
                    <td>
                        <span class="font-mono text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded-lg">{{ $order->kode_order }}</span>
                    </td>
                    <td>
                        <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                        @if($order->user->kelas)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->user->kelas }}</p>
                        @endif
                    </td>
                    <td>
                        <div class="text-xs text-gray-600 space-y-0.5">
                            @foreach($order->items as $item)
                            <div>{{ $item->qty }}x {{ $item->nama_menu }}</div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <span class="font-semibold text-orange-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        @if($order->waktu_pengambilan === 'istirahat_1')
                            <span class="badge bg-blue-100 text-blue-700">Istirahat 1</span>
                        @else
                            <span class="badge bg-purple-100 text-purple-700">Istirahat 2</span>
                        @endif
                        <p class="text-[10px] text-gray-400 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="text-center">
                        @if($order->status === 'paid')
                            <span class="badge bg-blue-100 text-blue-700">
                                <i class="ph ph-clock"></i> Dibayar
                            </span>
                        @elseif($order->status === 'preparing')
                            <span class="badge bg-indigo-100 text-indigo-700">
                                <i class="ph ph-fork-knife"></i> Disiapkan
                            </span>
                        @elseif($order->status === 'ready')
                            <span class="badge bg-green-100 text-green-700">
                                <i class="ph ph-check-circle"></i> Siap Ambil
                            </span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($order->status === 'paid')
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="preparing">
                            <button type="submit" class="btn-primary btn-sm bg-indigo-600 hover:bg-indigo-700 inline-flex items-center gap-1.5">
                                <i class="ph ph-fork-knife"></i> Siapkan
                            </button>
                        </form>
                        @elseif($order->status === 'preparing')
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="btn-primary btn-sm bg-green-600 hover:bg-green-700 inline-flex items-center gap-1.5">
                                <i class="ph ph-check"></i> Siap Ambil
                            </button>
                        </form>
                        @elseif($order->status === 'ready')
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn-primary btn-sm bg-gray-600 hover:bg-gray-700 inline-flex items-center gap-1.5">
                                <i class="ph ph-check-circle"></i> Selesai
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-14 text-center">
                        <i class="ph ph-check-circle text-4xl text-green-300 block mb-3"></i>
                        <p class="text-gray-400 font-medium">Tidak ada antrian pesanan saat ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Quick Action Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

    <a href="{{ route('admin.menu.create') }}"
       class="card p-5 flex flex-col items-center gap-3 text-center hover:border-orange-300 hover:shadow-md transition-all group cursor-pointer">
        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl group-hover:bg-orange-500 group-hover:text-white transition-colors">
            <i class="ph ph-plus"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Tambah Menu</p>
            <p class="text-xs text-gray-400 mt-0.5">Daftarkan menu baru</p>
        </div>
    </a>

    <a href="{{ route('admin.orders.index') }}"
       class="card p-5 flex flex-col items-center gap-3 text-center hover:border-orange-300 hover:shadow-md transition-all group cursor-pointer">
        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl group-hover:bg-blue-500 group-hover:text-white transition-colors">
            <i class="ph ph-receipt"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Semua Pesanan</p>
            <p class="text-xs text-gray-400 mt-0.5">Lihat antrian lengkap</p>
        </div>
    </a>

    <a href="{{ route('admin.laporan') }}"
       class="card p-5 flex flex-col items-center gap-3 text-center hover:border-orange-300 hover:shadow-md transition-all group cursor-pointer">
        <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl group-hover:bg-green-500 group-hover:text-white transition-colors">
            <i class="ph ph-chart-bar"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Laporan Harian</p>
            <p class="text-xs text-gray-400 mt-0.5">Ringkasan penjualan</p>
        </div>
    </a>

    <a href="{{ route('admin.withdrawal.index') }}"
       class="card p-5 flex flex-col items-center gap-3 text-center hover:border-orange-300 hover:shadow-md transition-all group cursor-pointer">
        <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center text-2xl group-hover:bg-pink-500 group-hover:text-white transition-colors">
            <i class="ph ph-money"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Tarik Dana</p>
            <p class="text-xs text-gray-400 mt-0.5">Cairkan saldo virtual</p>
        </div>
    </a>

</div>

@endsection
