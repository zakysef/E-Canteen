@extends('layouts.superadmin')

@section('title', 'Detail Pencairan')
@section('page-title', 'Detail Permintaan Pencairan')

@section('content-inner')
<div class="max-w-2xl">
    <a href="{{ route('superadmin.withdrawal.index') }}" class="text-sm text-indigo-600 hover:underline mb-6 block">← Kembali</a>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 mb-1">Penjual</p>
                <p class="font-semibold text-gray-800">{{ $withdrawalRequest->seller->nama_toko ?? $withdrawalRequest->seller->name }}</p>
                <p class="text-sm text-gray-500">{{ $withdrawalRequest->seller->email }}</p>
            </div>
            <span class="text-sm px-3 py-1.5 rounded-full font-medium
                {{ $withdrawalRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                   ($withdrawalRequest->status === 'approved' ? 'bg-blue-100 text-blue-700' :
                   ($withdrawalRequest->status === 'transferred' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                {{ ucfirst($withdrawalRequest->status) }}
            </span>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Jumlah Pencairan</span>
                <span class="font-bold text-orange-700 text-base">Rp {{ number_format($withdrawalRequest->jumlah, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Metode</span>
                <span class="font-medium">{{ $withdrawalRequest->metode_pembayaran }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Nomor Rekening/Akun</span>
                <span class="font-medium">{{ $withdrawalRequest->nomor_rekening }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Atas Nama</span>
                <span class="font-medium">{{ $withdrawalRequest->atas_nama }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Saldo Penjual Saat Ini</span>
                <span class="font-medium text-green-700">Rp {{ number_format($withdrawalRequest->seller->saldo, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($withdrawalRequest->status === 'pending')
        <div class="flex gap-3 pt-2" x-data="{ rejectOpen: false }">
            <form method="POST" action="{{ route('superadmin.withdrawal.approve', $withdrawalRequest) }}">
                @csrf @method('PATCH')
                <button class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 text-sm">
                    ✓ Setujui untuk Transfer
                </button>
            </form>
            <button @click="rejectOpen = !rejectOpen" class="bg-red-100 text-red-700 px-6 py-2.5 rounded-lg font-medium hover:bg-red-200 text-sm">
                ✕ Tolak
            </button>
            <div x-show="rejectOpen" class="mt-3 w-full">
                <form method="POST" action="{{ route('superadmin.withdrawal.reject', $withdrawalRequest) }}">
                    @csrf @method('PATCH')
                    <textarea name="catatan_admin" rows="3" placeholder="Alasan penolakan..." required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 mb-2"></textarea>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Konfirmasi Tolak</button>
                </form>
            </div>
        </div>
        @endif

        @if($withdrawalRequest->status === 'approved')
        <div class="border border-blue-200 bg-blue-50 rounded-xl p-4">
            <p class="text-sm font-semibold text-blue-800 mb-3">Upload Bukti Transfer</p>
            <form method="POST" action="{{ route('superadmin.withdrawal.transfer', $withdrawalRequest) }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="bukti_transfer" accept="image/*" required
                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 mb-3">
                <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-green-700 text-sm">
                    ✓ Konfirmasi Transfer Selesai
                </button>
            </form>
        </div>
        @endif

        @if($withdrawalRequest->bukti_transfer)
        <div>
            <p class="text-sm font-medium text-gray-700 mb-2">Bukti Transfer</p>
            <a href="{{ asset('storage/' . $withdrawalRequest->bukti_transfer) }}" target="_blank">
                <img src="{{ asset('storage/' . $withdrawalRequest->bukti_transfer) }}" alt="Bukti Transfer" class="max-h-64 rounded-lg border">
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
