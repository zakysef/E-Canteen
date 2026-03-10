@extends('layouts.user')

@section('title', 'Saldo & Transaksi')

@section('content-inner')
<div class="max-w-2xl mx-auto">
    {{-- Saldo Card --}}
    <div class="bg-gradient-to-br from-rose-500 via-pink-500 to-pink-400 rounded-2xl p-6 text-white mb-6 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute right-8 bottom-0 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-pink-100 mb-1">Saldo Anda</p>
                <p class="text-3xl font-bold">Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                <i class="ph ph-wallet text-white text-2xl"></i>
            </div>
        </div>
        <a href="{{ route('user.saldo.topup') }}" class="inline-flex items-center gap-2 mt-4 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl text-sm font-semibold transition-colors backdrop-blur-sm border border-white/20">
            <i class="ph ph-plus-circle text-base"></i>
            Top Up Saldo
        </a>
    </div>

    {{-- Top Up History --}}
    @if($topup_requests->count() > 0)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Permintaan Top Up Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($topup_requests as $req)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">Top Up · {{ str_replace('_', ' ', ucfirst($req->metode)) }}</p>
                    <p class="text-xs text-gray-400">{{ $req->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-rose-700 text-sm">+Rp {{ number_format($req->jumlah, 0, ',', '.') }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : ($req->status === 'approved' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Transaction History --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Riwayat Transaksi</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($transactions as $tx)
            <div class="px-5 py-3.5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ in_array($tx->tipe, ['topup', 'refund']) ? 'bg-rose-100' : 'bg-gray-100' }}">
                        <i class="{{ in_array($tx->tipe, ['topup', 'refund']) ? 'ph ph-arrow-circle-down text-rose-500' : 'ph ph-arrow-circle-up text-gray-400' }} text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $tx->tipe_label }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $tx->keterangan }}</p>
                        <p class="text-xs text-gray-400">{{ $tx->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold {{ in_array($tx->tipe, ['topup', 'refund']) ? 'text-rose-600' : 'text-red-500' }}">
                        {{ in_array($tx->tipe, ['topup', 'refund']) ? '+' : '-' }}Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400">Saldo: Rp {{ number_format($tx->saldo_sesudah, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <p class="px-5 py-8 text-center text-gray-400">Belum ada transaksi.</p>
            @endforelse
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $transactions->links() }}</div>
    </div>
</div>
@endsection
