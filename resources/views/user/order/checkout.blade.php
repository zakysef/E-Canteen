@extends('layouts.user')

@section('title', 'Konfirmasi Pesanan')

@section('content-inner')
<div class="max-w-lg mx-auto">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Konfirmasi Pesanan</h2>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-4">
        <h3 class="font-semibold text-gray-700 mb-4 text-sm uppercase tracking-wide">Detail Pesanan</h3>
        <div class="divide-y divide-gray-50 mb-4">
            @foreach($itemList as $item)
            <div class="py-2.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $item['menu']->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $item['qty'] }} x Rp {{ number_format($item['menu']->harga, 0, ',', '.') }}</p>
                </div>
                <p class="font-semibold text-gray-800">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
        <div class="border-t border-gray-200 pt-4 flex justify-between font-bold text-base">
            <span>Total</span>
            <span class="text-orange-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-4">
        <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-500">Saldo Anda</span>
            <span class="font-semibold">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-500">Total Bayar</span>
            <span class="font-semibold text-orange-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm font-bold border-t border-gray-100 pt-2">
            <span>Sisa Saldo</span>
            <span class="{{ auth()->user()->saldo >= $totalHarga ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format(auth()->user()->saldo - $totalHarga, 0, ',', '.') }}
            </span>
        </div>
        @if(auth()->user()->saldo < $totalHarga)
        <div class="mt-3 bg-red-50 text-red-600 text-xs px-3 py-2 rounded-lg border border-red-100">
            Saldo tidak mencukupi. <a href="{{ route('user.saldo.topup') }}" class="font-semibold underline">Top Up sekarang →</a>
        </div>
        @endif
    </div>

    <form method="POST" action="{{ route('user.order.store') }}">
        @csrf
        <input type="hidden" name="seller_id" value="{{ $sellerId }}">
        <input type="hidden" name="waktu_pengambilan" value="{{ $data['waktu_pengambilan'] }}">
        @if(isset($data['catatan']))
        <input type="hidden" name="catatan" value="{{ $data['catatan'] }}">
        @endif
        @foreach($itemList as $item)
        <input type="hidden" name="items[{{ $item['menu']->id }}][menu_id]" value="{{ $item['menu']->id }}">
        <input type="hidden" name="items[{{ $item['menu']->id }}][qty]" value="{{ $item['qty'] }}">
        @endforeach

        <div class="bg-orange-50 rounded-xl p-4 mb-4 text-sm">
            <p class="font-medium text-orange-800">Waktu Pengambilan:
                <span class="font-bold">{{ $data['waktu_pengambilan'] === 'istirahat_1' ? 'Istirahat 1' : 'Istirahat 2' }}</span>
            </p>
            <p class="text-orange-600 text-xs mt-1">Pesanan akan siap dijemput saat istirahat sesuai pilihan di atas.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('user.catalog') }}" class="flex-1 text-center border border-gray-300 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-50 text-sm">
                ← Ubah Pesanan
            </a>
            <button type="submit" {{ auth()->user()->saldo < $totalHarga ? 'disabled' : '' }}
                class="flex-1 bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                Bayar & Pesan
            </button>
        </div>
    </form>
</div>
@endsection
