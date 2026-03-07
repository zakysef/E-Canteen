@extends('layouts.superadmin')

@section('title', 'Detail Top Up')
@section('page-title', 'Detail Permintaan Top Up')

@section('content-inner')
<div class="max-w-2xl">
    <a href="{{ route('superadmin.topup.index') }}" class="text-sm text-indigo-600 hover:underline mb-6 block">← Kembali</a>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 mb-1">Pemohon</p>
                <p class="font-semibold text-gray-800">{{ $topupRequest->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $topupRequest->user->email }}</p>
            </div>
            <span class="text-sm px-3 py-1.5 rounded-full font-medium
                {{ $topupRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($topupRequest->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                {{ ucfirst($topupRequest->status) }}
            </span>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Jumlah Top Up</span>
                <span class="font-bold text-green-700 text-base">Rp {{ number_format($topupRequest->jumlah, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Metode</span>
                <span class="font-medium">{{ str_replace('_', ' ', ucfirst($topupRequest->metode)) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Nama Pengirim</span>
                <span class="font-medium">{{ $topupRequest->nama_pengirim ?? '-' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Tanggal Ajuan</span>
                <span>{{ $topupRequest->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        @if($topupRequest->bukti_url)
        <div>
            <p class="text-sm font-medium text-gray-700 mb-2">Bukti Transfer</p>
            <a href="{{ $topupRequest->bukti_url }}" target="_blank">
                <img src="{{ $topupRequest->bukti_url }}" alt="Bukti Transfer" class="max-h-72 rounded-lg border border-gray-200 hover:opacity-90 transition">
            </a>
        </div>
        @endif

        @if($topupRequest->status === 'pending')
        <div class="flex gap-3 pt-2">
            <form method="POST" action="{{ route('superadmin.topup.approve', $topupRequest) }}">
                @csrf @method('PATCH')
                <button class="bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-green-700 text-sm">
                    ✓ Setujui
                </button>
            </form>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="bg-red-100 text-red-700 px-6 py-2.5 rounded-lg font-medium hover:bg-red-200 text-sm">
                    ✕ Tolak
                </button>
                <div x-show="open" class="mt-3">
                    <form method="POST" action="{{ route('superadmin.topup.reject', $topupRequest) }}">
                        @csrf @method('PATCH')
                        <textarea name="catatan_admin" rows="3" placeholder="Alasan penolakan..." required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 mb-2"></textarea>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Konfirmasi Tolak</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if($topupRequest->catatan_admin)
        <div class="bg-red-50 rounded-lg p-3 border border-red-100">
            <p class="text-xs text-red-600 font-medium mb-1">Catatan Admin:</p>
            <p class="text-sm text-red-700">{{ $topupRequest->catatan_admin }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
