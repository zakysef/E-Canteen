@extends('layouts.admin')

@section('title', 'Antrian Pesanan')
@section('page-title', 'Antrian Pesanan')

@section('content-inner')

{{-- Filter row --}}
<div class="flex flex-wrap gap-3 items-start mb-6">

    {{-- Status tabs --}}
    <div class="flex items-center gap-1.5 flex-wrap">
        @php
            $statusFilter = request('status', '');
            $statusOptions = [
                ''           => ['label' => 'Semua',          'dot' => 'bg-gray-400'],
                'paid'       => ['label' => 'Dibayar',        'dot' => 'bg-pink-400'],
                'preparing'  => ['label' => 'Sedang Disiapkan','dot' => 'bg-rose-400'],
                'ready'      => ['label' => 'Siap Diambil',   'dot' => 'bg-rose-500'],
                'completed'  => ['label' => 'Selesai',        'dot' => 'bg-gray-400'],
            ];
        @endphp

        @foreach($statusOptions as $val => $cfg)
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status','page'), ['status' => $val])) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-colors border
                  {{ $statusFilter === $val
                       ? 'bg-rose-600 text-white border-rose-600'
                       : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }} {{ $statusFilter === $val ? 'bg-white' : '' }}"></span>
            {{ $cfg['label'] }}
        </a>
        @endforeach
    </div>

    {{-- Waktu Pengambilan filter --}}
    <div class="flex items-center gap-1.5 ml-auto">
        @php $waktuFilter = request('waktu', ''); @endphp
        <a href="{{ route('admin.orders.index', array_merge(request()->except('waktu','page'), ['waktu' => ''])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold border transition-colors
                  {{ $waktuFilter === '' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50' }}">
            Semua Waktu
        </a>
        <a href="{{ route('admin.orders.index', array_merge(request()->except('waktu','page'), ['waktu' => 'istirahat_1'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold border transition-colors
                  {{ $waktuFilter === 'istirahat_1' ? 'bg-pink-600 text-white border-pink-600' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50' }}">
            <i class="ph ph-clock mr-1"></i>Istirahat 1
        </a>
        <a href="{{ route('admin.orders.index', array_merge(request()->except('waktu','page'), ['waktu' => 'istirahat_2'])) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold border transition-colors
                  {{ $waktuFilter === 'istirahat_2' ? 'bg-rose-500 text-white border-rose-500' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50' }}">
            <i class="ph ph-clock mr-1"></i>Istirahat 2
        </a>
    </div>

</div>

{{-- Orders --}}
@if($orders->count() > 0)

<div class="space-y-4">
    @foreach($orders as $order)
    @php
        $waktuLabel  = $order->waktu_pengambilan === 'istirahat_1' ? 'Istirahat 1' : 'Istirahat 2';
        $waktuColor  = $order->waktu_pengambilan === 'istirahat_1' ? 'bg-pink-100 text-pink-700' : 'bg-rose-100 text-rose-700';

        $statusConfig = match($order->status) {
            'pending'   => ['bg-yellow-100 text-yellow-700', 'Menunggu Pembayaran'],
            'paid'      => ['bg-pink-100 text-pink-700',     'Dibayar'],
            'preparing' => ['bg-rose-100 text-rose-700',     'Sedang Disiapkan'],
            'ready'     => ['bg-green-100 text-green-700',   'Siap Diambil'],
            'completed' => ['bg-gray-100 text-gray-700',     'Selesai'],
            'cancelled' => ['bg-red-100 text-red-700',       'Dibatalkan'],
            default     => ['bg-gray-100 text-gray-600',     ucfirst($order->status)],
        };
    @endphp

    <div class="card p-5">
        <div class="flex flex-wrap items-start gap-3 mb-3">

            {{-- Left: order info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="font-mono text-xs text-gray-500 bg-gray-50 px-2 py-0.5 rounded-lg border border-gray-100">
                        {{ $order->kode_order }}
                    </span>
                    <span class="badge {{ $statusConfig[0] }}">{{ $statusConfig[1] }}</span>
                    <span class="badge {{ $waktuColor }}">
                        <i class="ph ph-clock text-[11px]"></i> {{ $waktuLabel }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <p class="font-semibold text-gray-800">{{ $order->user->name }}</p>
                    @if($order->user->kelas)
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $order->user->kelas }}</span>
                    @endif
                </div>
            </div>

            {{-- Right: total + timestamp --}}
            <div class="text-right shrink-0">
                <p class="font-bold text-rose-600 text-base">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M, H:i') }}</p>
            </div>

        </div>

        {{-- Items --}}
        <div class="bg-pink-50/60 rounded-xl px-4 py-2.5 mb-3 border border-pink-100">
            <div class="flex flex-wrap gap-x-4 gap-y-1">
                @foreach($order->items as $item)
                <span class="text-sm text-gray-700">
                    <span class="font-semibold text-rose-500">{{ $item->qty }}x</span>
                    {{ $item->nama_menu }}
                </span>
                @endforeach
            </div>
        </div>

        {{-- Action row --}}
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <a href="{{ route('admin.orders.show', $order) }}"
               class="text-xs text-rose-600 hover:underline font-medium inline-flex items-center gap-1">
                <i class="ph ph-receipt"></i> Lihat Detail
            </a>

            <div class="flex gap-2">
                @if($order->status === 'paid')
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit"
                            class="btn-primary btn-sm inline-flex items-center gap-1.5">
                        <i class="ph ph-fork-knife"></i> Mulai Siapkan
                    </button>
                </form>

                @elseif($order->status === 'preparing')
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="ready">
                    <button type="submit"
                            class="btn-sm inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1.5 rounded-lg transition-colors">
                        <i class="ph ph-check"></i> Tandai Siap Ambil
                    </button>
                </form>

                @elseif($order->status === 'ready')
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit"
                            class="btn-sm inline-flex items-center gap-1.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-3 py-1.5 rounded-lg transition-colors">
                        <i class="ph ph-check-circle"></i> Selesaikan
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div class="mt-6">{{ $orders->withQueryString()->links() }}</div>

@else
<div class="card py-20 flex flex-col items-center justify-center text-center">
    <i class="ph ph-receipt text-5xl text-gray-200 mb-4 block"></i>
    <h3 class="font-semibold text-gray-500 mb-1">Tidak ada pesanan</h3>
    <p class="text-sm text-gray-400">Tidak ada pesanan yang cocok dengan filter yang dipilih.</p>
</div>
@endif

@endsection
