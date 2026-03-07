@extends('layouts.user')

@section('title', 'Top Up Saldo')

@section('content-inner')
<div class="max-w-lg mx-auto">
    <a href="{{ route('user.saldo') }}" class="text-sm text-orange-600 hover:underline mb-6 block">← Kembali ke Saldo</a>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-2">Top Up Saldo</h2>
        <p class="text-sm text-gray-500 mb-6">Transfer ke rekening sekolah, upload bukti, dan tunggu konfirmasi dari admin.</p>

        {{-- Info rekening --}}
        <div class="bg-orange-50 rounded-xl p-4 mb-6 border border-orange-100">
            <p class="text-xs font-semibold text-orange-700 uppercase tracking-wide mb-3">Rekening Tujuan Transfer</p>
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Bank</span><span class="font-semibold">BCA</span></div>
                <div class="flex justify-between"><span class="text-gray-500">No. Rekening</span><span class="font-semibold font-mono">1234 5678 90</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Atas Nama</span><span class="font-semibold">SMA Negeri 1 (Bendahara)</span></div>
            </div>
            <p class="text-xs text-orange-600 mt-3">* Nominal transfer harus sama persis dengan jumlah yang diisi di form.</p>
        </div>

        <form method="POST" action="{{ route('user.saldo.topup.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Top Up (Rp) <span class="text-red-400">*</span></label>
                <input type="number" name="jumlah" min="10000" max="5000000" step="1000" required
                    value="{{ old('jumlah') }}"
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('jumlah') border-red-400 @enderror"
                    placeholder="Misal: 50000">
                @error('jumlah')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([10000, 20000, 50000, 100000] as $nominal)
                <button type="button" onclick="document.querySelector('[name=jumlah]').value='{{ $nominal }}'"
                    class="text-sm text-center border border-orange-200 text-orange-600 py-2 rounded-lg hover:bg-orange-50 font-medium">
                    Rp {{ number_format($nominal, 0, ',', '.') }}
                </button>
                @endforeach
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Transfer <span class="text-red-400">*</span></label>
                <select name="metode" required class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="transfer_bank">Transfer Bank</option>
                    <option value="e_wallet">E-Wallet (GoPay/OVO/Dana)</option>
                    <option value="tunai">Tunai (ke Bendahara)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Pengirim <span class="text-red-400">*</span></label>
                <input type="text" name="nama_pengirim" value="{{ old('nama_pengirim', auth()->user()->name) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bukti Transfer (foto) <span class="text-red-400">*</span></label>
                <input type="file" name="bukti_transfer" accept="image/*" required
                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-500 file:text-white hover:file:bg-orange-600">
                @error('bukti_transfer')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600">
                Kirim Permintaan Top Up
            </button>
        </form>
    </div>
</div>
@endsection
