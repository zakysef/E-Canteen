@extends('layouts.admin')

@section('title', 'Laporan Harian — ' . $tanggal)
@section('page-title', 'Laporan Harian')

@section('content-inner')

{{-- Header: date picker + print --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    <form method="GET" action="{{ route('admin.laporan') }}"
          class="flex items-center gap-2">
        <div class="relative">
            <i class="ph ph-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                   class="form-input pl-9 w-auto"
                   max="{{ now()->toDateString() }}">
        </div>
        <button type="submit" class="btn-primary inline-flex items-center gap-2">
            <i class="ph ph-funnel"></i> Tampilkan
        </button>
    </form>

    <button onclick="window.print()"
            class="btn-secondary inline-flex items-center gap-2 print:hidden">
        <i class="ph ph-printer"></i> Cetak Laporan
    </button>

</div>

{{-- Summary cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

    <div class="stat-card">
        <div class="stat-icon bg-rose-100 text-rose-600">
            <i class="ph ph-chart-bar"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium mb-0.5">Total Pendapatan</p>
            <p class="text-xl font-bold text-rose-700">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-pink-100 text-pink-600">
            <i class="ph ph-fork-knife"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium mb-0.5">Total Porsi Terjual</p>
            <p class="text-xl font-bold text-pink-700">{{ $total_porsi }} porsi</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-rose-100 text-rose-600">
            <i class="ph ph-check-circle"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium mb-0.5">Pesanan Selesai</p>
            <p class="text-xl font-bold text-rose-700">{{ $orders->count() }}</p>
        </div>
    </div>

</div>

@if($per_menu->count() > 0)

    {{-- Per-menu breakdown --}}
    <div class="card overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-pink-100 flex items-center gap-2">
            <i class="ph ph-chart-pie text-rose-500"></i>
            <h3 class="font-semibold text-gray-800">Rekap Per Menu</h3>
        </div>
        <table class="data-table w-full">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th class="text-right">Qty Terjual</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($per_menu->sortByDesc(fn($d) => $d['qty']) as $nama => $data)
                <tr>
                    <td>
                        <span class="font-medium text-gray-800">{{ $nama }}</span>
                    </td>
                    <td class="text-right">
                        <span class="font-semibold text-rose-600">{{ $data['qty'] }}</span>
                        <span class="text-gray-400 text-xs ml-1">porsi</span>
                    </td>
                    <td class="text-right font-semibold text-rose-700">
                        Rp {{ number_format($data['subtotal'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="font-bold text-gray-800">Total</td>
                    <td class="text-right font-bold text-pink-700">{{ $total_porsi }} porsi</td>
                    <td class="text-right font-bold text-rose-700">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Orders list --}}
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-pink-100 flex items-center gap-2">
            <i class="ph ph-receipt text-rose-500"></i>
            <h3 class="font-semibold text-gray-800">Daftar Pesanan Selesai</h3>
            <span class="badge bg-gray-100 text-gray-600 ml-1">{{ $orders->count() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pemesan</th>
                        <th>Item</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Waktu Ambil</th>
                        <th>Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <span class="font-mono text-xs text-gray-500 bg-gray-50 px-2 py-0.5 rounded-lg">
                                {{ $order->kode_order }}
                            </span>
                        </td>
                        <td>
                            <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                            @if($order->user->kelas)
                            <p class="text-xs text-gray-400">{{ $order->user->kelas }}</p>
                            @endif
                        </td>
                        <td>
                            <div class="text-xs text-gray-600 space-y-0.5">
                                @foreach($order->items as $item)
                                <div>{{ $item->qty }}x {{ $item->nama_menu }}</div>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-right font-semibold text-rose-700">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($order->waktu_pengambilan === 'istirahat_1')
                                <span class="badge bg-pink-100 text-pink-700">Istirahat 1</span>
                            @else
                                <span class="badge bg-rose-100 text-rose-700">Istirahat 2</span>
                            @endif
                        </td>
                        <td class="text-xs text-gray-400">
                            {{ $order->completed_at ? $order->completed_at->format('H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@else
    {{-- Empty state --}}
    <div class="card py-20 flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 rounded-full bg-pink-50 flex items-center justify-center mb-5">
            <i class="ph ph-chart-bar text-4xl text-pink-200"></i>
        </div>
        <h3 class="font-semibold text-gray-500 text-lg mb-2">Tidak ada data penjualan</h3>
        <p class="text-sm text-gray-400">
            Belum ada pesanan yang selesai pada tanggal
            <strong>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</strong>.
        </p>
    </div>
@endif

@push('scripts')
<style>
    @media print {
        aside, header, .print\:hidden { display: none !important; }
        main { padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
    }
</style>
@endpush

@endsection
