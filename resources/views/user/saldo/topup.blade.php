@extends('layouts.user')

@section('page-title', 'Top Up Saldo')
@section('page-subtitle', 'Tambah saldo virtual untuk melakukan pemesanan')

@section('content-inner')
<div class="max-w-xl mx-auto space-y-5"
     x-data="{
        metode: '{{ old('metode', '') }}',
        selectedKey: {{ old('metode') ? "'".old('metode')."'" : 'null' }},
        jumlah: '{{ old('jumlah', '') }}'
     }">

    <a href="{{ route('user.saldo') }}" class="inline-flex items-center gap-1.5 text-sm text-pink-600 hover:underline">
        <i class="ph ph-arrow-left"></i> Kembali ke Saldo
    </a>

    {{-- Flash & Errors --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="ph ph-check-circle text-lg text-green-500"></i> {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
        <p class="font-semibold mb-1"><i class="ph ph-warning mr-1"></i>Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- ── Step 1: Pilih Metode ── --}}
    <div class="bg-white rounded-2xl border border-pink-100 shadow-sm p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Langkah 1 — Pilih Metode Pembayaran</p>

        @php
        $banks    = $paymentSettings->where('type', 'bank');
        $ewallets = $paymentSettings->where('type', 'ewallet');
        @endphp

        <div class="space-y-4">

            {{-- Bank Transfer --}}
            @if($banks->isNotEmpty())
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-2 flex items-center gap-1.5">
                    <i class="ph ph-bank text-blue-500"></i> Transfer Bank
                </p>
                <div class="space-y-2">
                    @foreach($banks as $setting)
                    <label @click="metode = 'transfer_bank'; selectedKey = '{{ $setting->key }}'"
                           class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                           :class="selectedKey === '{{ $setting->key }}' ? 'border-blue-400 bg-blue-50' : 'border-gray-100 hover:border-blue-200'">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                            <i class="ph ph-bank text-blue-600 text-base"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm">{{ $setting->label }}</p>
                            @if($setting->account_number)
                            <p class="font-mono text-sm text-gray-700 tracking-wide mt-0.5">{{ $setting->account_number }}</p>
                            @endif
                            @if($setting->account_name)
                            <p class="text-xs text-gray-500">a.n. {{ $setting->account_name }}</p>
                            @endif
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 mt-0.5 transition-all shrink-0 flex items-center justify-center"
                             :class="selectedKey === '{{ $setting->key }}' ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                            <i class="ph ph-check text-white text-xs" x-show="selectedKey === '{{ $setting->key }}'"></i>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- E-Wallet --}}
            @if($ewallets->isNotEmpty())
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-2 flex items-center gap-1.5">
                    <i class="ph ph-qr-code text-purple-500"></i> E-Wallet / QRIS
                </p>
                <div class="space-y-2">
                    @foreach($ewallets as $setting)
                    <label @click="metode = 'e_wallet'; selectedKey = '{{ $setting->key }}'"
                           class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                           :class="selectedKey === '{{ $setting->key }}' ? 'border-purple-400 bg-purple-50' : 'border-gray-100 hover:border-purple-200'">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
                            <i class="ph ph-qr-code text-purple-600 text-base"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm">{{ $setting->label }}</p>
                            @if($setting->account_number)
                            <p class="font-mono text-sm text-gray-700 mt-0.5">{{ $setting->account_number }}</p>
                            @endif
                            @if($setting->account_name)
                            <p class="text-xs text-gray-500">a.n. {{ $setting->account_name }}</p>
                            @endif
                            @if($setting->qr_code)
                            <div x-show="selectedKey === '{{ $setting->key }}'" x-transition class="mt-3">
                                <img src="{{ $setting->qr_code_url }}" alt="QR Code {{ $setting->label }}"
                                     class="w-44 h-44 object-contain border-2 border-purple-200 rounded-2xl bg-white p-2 shadow-sm">
                                <p class="text-xs text-purple-600 mt-2 flex items-center gap-1">
                                    <i class="ph ph-device-mobile-camera"></i>
                                    Scan dengan aplikasi {{ $setting->label }}
                                </p>
                            </div>
                            @endif
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 mt-0.5 transition-all shrink-0 flex items-center justify-center"
                             :class="selectedKey === '{{ $setting->key }}' ? 'border-purple-500 bg-purple-500' : 'border-gray-300'">
                            <i class="ph ph-check text-white text-xs" x-show="selectedKey === '{{ $setting->key }}'"></i>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Tunai --}}
            <div>
                @if($banks->isEmpty() && $ewallets->isEmpty())
                {{-- No bank/ewallet configured, still show tunai as only option --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-700 mb-3">
                    <i class="ph ph-info mr-1"></i>
                    Belum ada metode transfer yang tersedia. Gunakan pembayaran tunai ke bendahara.
                </div>
                @else
                <p class="text-xs font-semibold text-gray-500 mb-2 flex items-center gap-1.5">
                    <i class="ph ph-hand-coins text-amber-500"></i> Tunai
                </p>
                @endif
                <label @click="metode = 'tunai'; selectedKey = 'tunai'"
                       class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                       :class="selectedKey === 'tunai' ? 'border-amber-400 bg-amber-50' : 'border-gray-100 hover:border-amber-200'">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                        <i class="ph ph-hand-coins text-amber-600 text-base"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">Bayar Tunai ke Bendahara</p>
                        <p class="text-xs text-gray-500 mt-0.5">Datangi bendahara sekolah secara langsung. Saldo akan ditambahkan setelah dikonfirmasi.</p>
                        <div x-show="selectedKey === 'tunai'" x-transition
                             class="mt-2 bg-amber-100 rounded-lg px-3 py-2 text-xs text-amber-800">
                            <i class="ph ph-warning mr-1"></i>
                            Kirim permintaan ini, lalu tunjukkan kepada bendahara untuk dikonfirmasi.
                        </div>
                    </div>
                    <div class="w-5 h-5 rounded-full border-2 mt-0.5 transition-all shrink-0 flex items-center justify-center"
                         :class="selectedKey === 'tunai' ? 'border-amber-500 bg-amber-500' : 'border-gray-300'">
                        <i class="ph ph-check text-white text-xs" x-show="selectedKey === 'tunai'"></i>
                    </div>
                </label>
            </div>

        </div>
    </div>

    {{-- ── Step 2: Form ── --}}
    <div x-show="selectedKey !== null" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-2xl border border-pink-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Langkah 2 — Detail & Konfirmasi</p>

            <form method="POST" action="{{ route('user.saldo.topup.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="metode" :value="metode">

                {{-- Jumlah --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Top Up (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="jumlah" x-model="jumlah" min="10000" max="5000000" step="1000" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 @error('jumlah') border-red-400 @enderror"
                           placeholder="Min Rp 10.000">
                    @error('jumlah')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        @foreach([10000, 20000, 50000, 100000] as $nominal)
                        <button type="button" @click="jumlah = '{{ $nominal }}'"
                                class="text-xs text-center border border-pink-200 text-pink-600 py-1.5 rounded-lg hover:bg-pink-50 font-medium transition">
                            Rp {{ number_format($nominal, 0, ',', '.') }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Nama Pengirim (hanya untuk transfer) --}}
                <div x-show="metode !== 'tunai'" x-transition>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pengirim <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_pengirim" value="{{ old('nama_pengirim', auth()->user()->name) }}"
                           :required="metode !== 'tunai'"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                    <p class="text-xs text-gray-400 mt-1">Sesuai nama pemilik rekening/akun yang digunakan.</p>
                </div>

                {{-- Bukti Transfer (hanya untuk transfer) --}}
                <div x-show="metode !== 'tunai'" x-transition>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bukti Transfer <span class="text-red-400">*</span></label>
                    <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 hover:border-pink-300 rounded-xl p-6 cursor-pointer transition-colors bg-gray-50 hover:bg-pink-50/30">
                        <i class="ph ph-upload-simple text-2xl text-gray-400 mb-2"></i>
                        <span class="text-sm text-gray-500 font-medium">Klik untuk upload foto bukti transfer</span>
                        <span class="text-xs text-gray-400 mt-1">JPG, PNG, maks 2MB</span>
                        <input type="file" name="bukti_transfer" accept="image/*"
                               :required="metode !== 'tunai'"
                               class="hidden">
                    </label>
                    @error('bukti_transfer')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <p class="text-xs text-gray-400">
                    <i class="ph ph-seal-check text-green-400 mr-1"></i>
                    Saldo akan ditambahkan setelah pembayaran diverifikasi oleh admin.
                </p>

                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-3 rounded-xl font-semibold text-sm transition flex items-center justify-center gap-2">
                    <i class="ph ph-paper-plane-right"></i>
                    <span x-text="metode === 'tunai' ? 'Kirim Permintaan Top Up Tunai' : 'Kirim Permintaan Top Up'">
                        Kirim Permintaan Top Up
                    </span>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
