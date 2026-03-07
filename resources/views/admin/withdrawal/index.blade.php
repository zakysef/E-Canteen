@extends('layouts.admin')

@section('title', 'Tarik Dana')
@section('page-title', 'Pencairan Dana')

@section('content-inner')
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Form --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="bg-green-50 rounded-xl p-4 mb-5">
                <p class="text-xs text-gray-500 mb-1">Saldo Tersedia</p>
                <p class="text-2xl font-bold text-green-700">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</p>
            </div>
            <h3 class="font-semibold text-gray-800 mb-4">Ajukan Pencairan</h3>
            <form method="POST" action="{{ route('admin.withdrawal.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah (min. Rp 10.000)</label>
                    <input type="number" name="jumlah" min="10000" max="{{ auth()->user()->saldo }}" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 @error('jumlah') border-red-400 @enderror"
                        placeholder="Rp">
                    @error('jumlah')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran</label>
                    <input type="text" name="metode_pembayaran" required placeholder="BCA / BRI / GoPay / OVO"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Rekening / Akun</label>
                    <input type="text" name="nomor_rekening" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                        placeholder="Contoh: 0812345678">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Atas Nama</label>
                    <input type="text" name="atas_nama" required value="{{ auth()->user()->name }}"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>
                <button type="submit" class="w-full bg-orange-500 text-white py-2.5 rounded-lg font-semibold hover:bg-orange-600">
                    Ajukan Pencairan
                </button>
            </form>
        </div>
    </div>

    {{-- History --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Riwayat Pencairan</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($requests as $req)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Rp {{ number_format($req->jumlah, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ $req->metode_pembayaran }} · {{ $req->nomor_rekening }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $req->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($req->status === 'approved' ? 'bg-blue-100 text-blue-700' :
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
