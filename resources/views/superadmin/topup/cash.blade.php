@extends('layouts.superadmin')

@section('page-title', 'Top-Up Tunai')
@section('page-subtitle', 'Tambahkan saldo virtual pengguna secara langsung (pembayaran tunai ke bendahara)')

@section('content-inner')
<div x-data="{ selectedUser: null, jumlah: '', catatan: '' }" class="space-y-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="ph ph-check-circle text-lg text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Info Banner --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-start gap-3">
        <i class="ph ph-info text-amber-500 text-xl shrink-0 mt-0.5"></i>
        <p class="text-sm text-amber-800">
            Fitur ini digunakan oleh <strong>bendahara sekolah</strong> untuk menambahkan saldo virtual ke akun siswa/guru yang telah membayar secara tunai.
            Transaksi akan tercatat otomatis dalam riwayat top-up.
        </p>
    </div>

    {{-- Search + Table --}}
    <div class="bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between gap-4">
            <h3 class="font-semibold text-gray-800">Daftar Pengguna (Siswa / Guru)</h3>
            <form method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / NIS..."
                           class="pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-300 w-56">
                </div>
                <button type="submit" class="bg-rose-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-rose-700 transition">Cari</button>
                @if($search)
                <a href="{{ route('superadmin.topup.cash') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 py-2">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Nama</th>
                        <th class="px-5 py-3 text-left">NIS / Identifier</th>
                        <th class="px-5 py-3 text-left">Kelas</th>
                        <th class="px-5 py-3 text-right">Saldo Saat Ini</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-pink-50/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 font-mono text-gray-600">{{ $user->identifier ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $user->kelas ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-800">
                            Rp {{ number_format($user->saldo, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button @click="selectedUser = {{ $user->toJson() }}"
                                    class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                <i class="ph ph-plus-circle"></i>
                                Top-Up
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                            <i class="ph ph-users text-3xl block mb-2"></i>
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- Top-Up Modal --}}
    <div x-show="selectedUser !== null" x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div @click.outside="selectedUser = null" x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="font-semibold text-gray-800">
                    <i class="ph ph-coins text-rose-500 mr-2"></i>Top-Up Tunai
                </h3>
                <button @click="selectedUser = null" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            <template x-if="selectedUser">
                <div>
                    {{-- User Info --}}
                    <div class="px-5 pt-4 pb-3 bg-rose-50/50 border-b border-rose-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                                <span x-text="selectedUser.name.substring(0,2).toUpperCase()"></span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800" x-text="selectedUser.name"></p>
                                <p class="text-xs text-gray-500">
                                    Saldo: <span class="font-semibold text-rose-600"
                                        x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedUser.saldo)"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form :action="`/superadmin/topup/cash/${selectedUser.id}`" method="POST" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                Jumlah Top-Up (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah" x-model="jumlah"
                                   min="1000" max="10000000" step="500" placeholder="Contoh: 50000" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                            <div class="flex gap-2 mt-2 flex-wrap">
                                @foreach([10000, 20000, 50000, 100000, 200000] as $nominal)
                                <button type="button" @click="jumlah = {{ $nominal }}"
                                        class="text-xs px-2.5 py-1 rounded-lg border border-gray-200 hover:border-rose-300 hover:bg-rose-50 transition">
                                    Rp{{ number_format($nominal, 0, ',', '.') }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan (opsional)</label>
                            <input type="text" name="catatan" x-model="catatan" placeholder="Contoh: Pembayaran tunai Maret 2026"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                        </div>
                        <div class="bg-amber-50 rounded-lg px-4 py-3 text-xs text-amber-700">
                            <i class="ph ph-warning mr-1"></i>
                            Pastikan pengguna telah membayar secara tunai sebelum mengkonfirmasi top-up ini.
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                                <i class="ph ph-check mr-1"></i>Konfirmasi Top-Up
                            </button>
                            <button type="button" @click="selectedUser = null"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>

</div>
@endsection
