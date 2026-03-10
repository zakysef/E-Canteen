@extends('layouts.admin')

@section('title', 'Tarik Dana')
@section('page-title', 'Pencairan Dana')

@section('content-inner')
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Form --}}
    <div class="lg:col-span-1">
        <div class="card p-6">
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 rounded-xl p-4 mb-5 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i class="ph ph-wallet text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-pink-100 mb-0.5">Saldo Tersedia</p>
                        <p class="text-2xl font-bold">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <h3 class="font-semibold text-gray-800 mb-4">Ajukan Pencairan</h3>
            <form method="POST" action="{{ route('admin.withdrawal.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah (min. Rp 10.000)</label>
                    <input type="number" name="jumlah" min="10000" max="{{ auth()->user()->saldo }}" required
                        class="form-input @error('jumlah') error @enderror"
                        placeholder="Rp">
                    @error('jumlah')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran</label>
                    <input type="text" name="metode_pembayaran" required placeholder="BCA / BRI / GoPay / OVO"
                        class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Rekening / Akun</label>
                    <input type="text" name="nomor_rekening" required
                        class="form-input"
                        placeholder="Contoh: 0812345678">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Atas Nama</label>
                    <input type="text" name="atas_nama" required value="{{ auth()->user()->name }}"
                        class="form-input">
                </div>
                <button type="submit" class="btn-primary w-full py-2.5 flex items-center justify-center gap-2">
                    <i class="ph ph-paper-plane-right"></i>
                    Ajukan Pencairan
                </button>
            </form>
        </div>
    </div>

    {{-- History --}}
    <div class="lg:col-span-2">
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-pink-100 flex items-center gap-2">
                <i class="ph ph-clock-counter-clockwise text-rose-400"></i>
                <h3 class="font-semibold text-gray-800">Riwayat Pencairan</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($requests as $req)
                <div class="px-6 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center shrink-0">
                            <i class="ph ph-money text-rose-500 text-base"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($req->jumlah, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $req->metode_pembayaran }} · {{ $req->nomor_rekening }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium shrink-0
                        {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($req->status === 'approved' ? 'bg-pink-100 text-pink-700' :
                           ($req->status === 'transferred' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </div>
                @empty
                <p class="px-6 py-8 text-center text-gray-400">Belum ada riwayat pencairan.</p>
                @endforelse
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $requests->links() }}</div>
        </div>
    </div>
</div>
@endsection
