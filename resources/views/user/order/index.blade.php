@extends('layouts.user')

@section('title', 'Riwayat Pesanan')

@section('content-inner')
<h2 class="text-xl font-bold text-gray-800 mb-6">Riwayat Pesanan</h2>

<div class="space-y-4">
    @forelse($orders as $order)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 flex items-center justify-between border-b border-gray-50">
            <div>
                <p class="font-semibold text-gray-800 font-mono text-sm">{{ $order->kode_order }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-700">
                    {{ $order->status_label }}
                </span>
                <p class="text-xs text-gray-400 mt-1">{{ $order->waktu_pengambilan === 'istirahat_1' ? 'Istirahat 1' : 'Istirahat 2' }}</p>
            </div>
        </div>
        <div class="px-5 py-3">
            <div class="text-sm text-gray-600 mb-3">
                @foreach($order->items as $item)
                <span>{{ $item->qty }}x {{ $item->nama_menu }}</span>@if(!$loop->last), @endif
                @endforeach
            </div>
            <div class="flex items-center justify-between">
                <p class="font-bold text-rose-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                <div class="flex gap-2">
                    @if($order->status === 'paid')
                    <form method="POST" action="{{ route('user.order.cancel', $order) }}">
                        @csrf @method('PATCH')
                        <button type="submit" onclick="return confirm('Batalkan pesanan ini?')"
                            class="text-xs text-red-500 hover:underline border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50">
                            Batalkan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @if($order->status === 'ready')
        <div class="bg-rose-50 border-t border-rose-100 px-5 py-3 text-sm font-semibold text-rose-700 flex items-center gap-2">
            <i class="ph ph-check-circle text-base"></i> Pesanan siap diambil! Segera ke kantin.
        </div>
        @endif
    </div>
    @empty
    <div class="py-16 text-center">
        <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-receipt text-pink-400 text-3xl"></i>
        </div>
        <p class="text-gray-500 mb-4">Belum ada pesanan.</p>
        <a href="{{ route('user.catalog') }}" class="inline-flex items-center gap-2 bg-rose-500 text-white px-6 py-2.5 rounded-xl font-medium hover:bg-rose-600 transition-colors">
            <i class="ph ph-storefront text-base"></i>
            Mulai Pre-Order
        </a>
    </div>
    @endforelse
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endsection
