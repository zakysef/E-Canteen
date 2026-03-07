@extends('layouts.user')

@section('title', 'Detail Pesanan — ' . $order->kode_order)
@section('page-title', 'Detail Pesanan')

@section('content-inner')

<div class="max-w-3xl">

    {{-- Back link --}}
    <a href="{{ route('user.orders') }}"
       class="inline-flex items-center gap-2 text-sm text-pink-600 hover:text-pink-700 font-medium mb-6">
        <i class="ph ph-arrow-left"></i> Kembali ke Pesanan Saya
    </a>

    @php
        $statusConfig = match($order->status) {
            'pending'   => ['bg-yellow-100 text-yellow-700', 'Menunggu Pembayaran', 'ph-clock'],
            'paid'      => ['bg-blue-100 text-blue-700',     'Dibayar',             'ph-check'],
            'preparing' => ['bg-indigo-100 text-indigo-700', 'Sedang Disiapkan',    'ph-fork-knife'],
            'ready'     => ['bg-green-100 text-green-700',   'Siap Diambil',        'ph-check-circle'],
            'completed' => ['bg-gray-100 text-gray-700',     'Selesai',             'ph-check-circle'],
            'cancelled' => ['bg-red-100 text-red-700',       'Dibatalkan',          'ph-x-circle'],
            default     => ['bg-gray-100 text-gray-600',     ucfirst($order->status), 'ph-clock'],
        };
        $waktuLabel = $order->waktu_pengambilan === 'istirahat_1' ? 'Istirahat 1' : 'Istirahat 2';
        $waktuColor = $order->waktu_pengambilan === 'istirahat_1' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700';
    @endphp

    {{-- Order header card --}}
    <div class="card p-6 mb-5">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="font-mono text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-xl border border-gray-100">
                        {{ $order->kode_order }}
                    </span>
                    <span class="badge {{ $statusConfig[0] }}">
                        <i class="ph {{ $statusConfig[2] }}"></i>
                        {{ $statusConfig[1] }}
                    </span>
                </div>
                <p class="text-xs text-gray-400">
                    Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}
                </p>
            </div>

            {{-- Cancel button (only if paid) --}}
            @if($order->status === 'paid')
            <form method="POST" action="{{ route('user.order.cancel', $order) }}"
                  onsubmit="return confirm('Batalkan pesanan ini? Saldo akan dikembalikan.')">
                @csrf @method('PATCH')
                <button type="submit"
                        class="btn-danger inline-flex items-center gap-2">
                    <i class="ph ph-x-circle"></i> Batalkan Pesanan
                </button>
            </form>
            @endif
        </div>

        <div class="grid sm:grid-cols-2 gap-5">

            {{-- Seller info --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Informasi Penjual</p>
                <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr($order->seller->nama_toko ?? $order->seller->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">
                                {{ $order->seller->nama_toko ?? $order->seller->name }}
                            </p>
                            @if($order->seller->nama_toko)
                            <p class="text-xs text-gray-400">{{ $order->seller->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Waktu & Catatan --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Detail Pesanan</p>
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Waktu Pengambilan</span>
                        <span class="badge {{ $waktuColor }}">
                            <i class="ph ph-clock text-[11px]"></i> {{ $waktuLabel }}
                        </span>
                    </div>
                    @if($order->catatan)
                    <div>
                        <span class="text-xs text-gray-500 block mb-1">Catatan</span>
                        <p class="text-sm text-gray-700 bg-yellow-50 border border-yellow-100 rounded-lg px-3 py-2">
                            {{ $order->catatan }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Timeline --}}
        @php
            $timestamps = collect([
                ['label' => 'Dipesan',    'time' => $order->created_at],
                ['label' => 'Dibayar',    'time' => $order->paid_at ?? null],
                ['label' => 'Disiapkan',  'time' => null],
                ['label' => 'Siap Ambil', 'time' => $order->ready_at ?? null],
                ['label' => 'Selesai',    'time' => $order->completed_at ?? null],
            ])->filter(fn($t) => $t['time'] !== null);
        @endphp
        @if($timestamps->count() > 1)
        <div class="mt-5 pt-4 border-t border-pink-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Riwayat Status</p>
            <div class="flex items-center overflow-x-auto pb-1">
                @foreach($timestamps as $ts)
                <div class="flex items-center shrink-0">
                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center">
                            <i class="ph ph-check text-xs font-bold"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1 whitespace-nowrap">{{ $ts['label'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $ts['time']->format('H:i') }}</p>
                    </div>
                    @if(!$loop->last)
                    <div class="w-10 h-px bg-pink-200 shrink-0"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Items table --}}
    <div class="card overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-pink-100 flex items-center gap-2">
            <i class="ph ph-fork-knife text-pink-500"></i>
            <h3 class="font-semibold text-gray-800">Item Pesanan</h3>
            <span class="badge bg-pink-100 text-pink-700 ml-1">{{ $order->items->count() }} item</span>
        </div>
        <table class="data-table w-full">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <p class="font-medium text-gray-800">{{ $item->nama_menu }}</p>
                    </td>
                    <td class="text-center">
                        <span class="font-semibold text-pink-600">{{ $item->qty }}</span>
                    </td>
                    <td class="text-right text-gray-600">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td class="text-right font-semibold text-gray-800">
                        Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4 border-t border-pink-100 bg-pink-50/40">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Total Item: <span class="font-medium text-gray-700">{{ $order->items->sum('qty') }} porsi</span>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-0.5">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-pink-600">
                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
