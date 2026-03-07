@extends('layouts.base')

@section('title', 'Daftar')

@section('content')
<div class="min-h-screen flex"
     x-data="{ showPassword: false, showConfirm: false }">

    {{-- ============================================================ --}}
    {{-- LEFT PANEL — decorative (hidden on mobile) --}}
    {{-- ============================================================ --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 relative overflow-hidden flex-col items-center justify-center p-12 flex-shrink-0"
         style="background: linear-gradient(135deg, #be185d 0%, #db2777 50%, #f43f5e 100%);">

        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-10" aria-hidden="true"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

        {{-- Blobs --}}
        <div class="absolute -top-24 -left-24 w-80 h-80 bg-white/10 rounded-full blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl" aria-hidden="true"></div>

        {{-- Content --}}
        <div class="relative z-10 text-white text-center max-w-sm">

            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl">
                <i class="ph ph-fork-knife text-white text-4xl"></i>
            </div>

            <h2 class="text-4xl font-black mb-3 leading-tight">Bergabung<br>Sekarang!</h2>
            <p class="text-pink-100 text-base font-medium mb-2">Sistem Kantin Digital</p>
            <p class="text-pink-200 text-sm leading-relaxed mb-10">
                Daftar sekali, nikmati kemudahan pesan makan siang tanpa antri setiap hari.
            </p>

            {{-- Benefit list --}}
            <ul class="space-y-4 text-left">
                @foreach([
                    ['ph-identification-card', 'Daftar dengan NIS siswa'],
                    ['ph-wallet',              'Saldo digital untuk pembayaran'],
                    ['ph-bell',                'Notifikasi pesanan real-time'],
                    ['ph-history',             'Riwayat transaksi lengkap'],
                ] as [$icon, $text])
                <li class="flex items-center gap-3 text-sm text-pink-50">
                    <span class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="ph {{ $icon }} text-white text-base"></i>
                    </span>
                    {{ $text }}
                </li>
                @endforeach
            </ul>
        </div>

        <p class="absolute bottom-6 text-pink-200/60 text-xs">
            &copy; {{ date('Y') }} E-Canteen. Hak cipta dilindungi.
        </p>
    </div>

    {{-- ============================================================ --}}
    {{-- RIGHT PANEL — register form (scrollable) --}}
    {{-- ============================================================ --}}
    <div class="w-full lg:w-7/12 xl:w-1/2 flex items-start justify-center p-6 sm:p-10 bg-gradient-to-br from-pink-50 via-white to-rose-50 overflow-y-auto min-h-screen">

        <div class="w-full max-w-md py-8">

            {{-- Mobile logo --}}
            <div class="flex items-center justify-center gap-2.5 mb-8 lg:hidden">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md"
                     style="background: linear-gradient(135deg,#db2777,#f43f5e);">
                    <i class="ph ph-fork-knife text-white text-lg"></i>
                </div>
                <span class="text-2xl font-black text-gray-900">E-<span class="text-pink-600">Canteen</span></span>
            </div>

            {{-- Card --}}
            <div class="card p-8 sm:p-10 shadow-xl shadow-pink-100/50">

                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-gray-900 mb-1.5">Buat Akun Baru</h1>
                    <p class="text-sm text-gray-500">Isi formulir di bawah untuk mendaftarkan akunmu.</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                    @csrf

                    {{-- 1. Nama Lengkap --}}
                    <div>
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-user text-gray-400 text-base"></i>
                            </span>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   autocomplete="name"
                                   placeholder="Nama lengkap sesuai data sekolah"
                                   class="form-input pl-10 @error('name') error @enderror">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ph ph-warning-circle text-sm"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- 2. Email --}}
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-envelope text-gray-400 text-base"></i>
                            </span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   placeholder="email@sekolah.com"
                                   class="form-input pl-10 @error('email') error @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ph ph-warning-circle text-sm"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- 3. NIS --}}
                    <div>
                        <label for="identifier" class="form-label">
                            Nomor Induk Siswa (NIS)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-identification-card text-gray-400 text-base"></i>
                            </span>
                            <input type="text"
                                   id="identifier"
                                   name="identifier"
                                   value="{{ old('identifier') }}"
                                   maxlength="5"
                                   inputmode="numeric"
                                   pattern="[0-9]{5}"
                                   placeholder="5 digit NIS (contoh: 12345)"
                                   class="form-input pl-10 @error('identifier') error @enderror">
                        </div>
                        @error('identifier')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ph ph-warning-circle text-sm"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- 4. Jurusan --}}
                    <div>
                        <label for="kelas" class="form-label">Jurusan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-graduation-cap text-gray-400 text-base"></i>
                            </span>
                            <select id="kelas"
                                    name="kelas"
                                    class="form-input pl-10 pr-8 appearance-none @error('kelas') error @enderror">
                                <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>-- Pilih Jurusan --</option>
                                @foreach(['RPL' => 'RPL — Rekayasa Perangkat Lunak', 'DKV' => 'DKV — Desain Komunikasi Visual', 'AK' => 'AK — Akuntansi', 'MP' => 'MP — Manajemen Perkantoran', 'BR' => 'BR — Bisnis Ritel'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('kelas') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-caret-down text-gray-400 text-sm"></i>
                            </span>
                        </div>
                        @error('kelas')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ph ph-warning-circle text-sm"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- 5. No. Telepon (optional) --}}
                    <div>
                        <label for="phone" class="form-label">
                            No. Telepon
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-phone text-gray-400 text-base"></i>
                            </span>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   autocomplete="tel"
                                   placeholder="08xx-xxxx-xxxx"
                                   class="form-input pl-10 @error('phone') error @enderror">
                        </div>
                        @error('phone')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ph ph-warning-circle text-sm"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- 6. Password --}}
                    <div>
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-lock text-gray-400 text-base"></i>
                            </span>
                            <input :type="showPassword ? 'text' : 'password'"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Minimal 8 karakter"
                                   class="form-input pl-10 pr-11 @error('password') error @enderror">
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                <i :class="showPassword ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-base"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ph ph-warning-circle text-sm"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- 7. Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-lock text-gray-400 text-base"></i>
                            </span>
                            <input :type="showConfirm ? 'text' : 'password'"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Ulangi kata sandi"
                                   class="form-input pl-10 pr-11 @error('password_confirmation') error @enderror">
                            <button type="button"
                                    @click="showConfirm = !showConfirm"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                    :aria-label="showConfirm ? 'Sembunyikan konfirmasi' : 'Tampilkan konfirmasi'">
                                <i :class="showConfirm ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-base"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="ph ph-warning-circle text-sm"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-1">
                        <button type="submit"
                                class="btn-primary w-full py-3 text-base flex items-center justify-center gap-2 rounded-xl shadow-md shadow-pink-200/50 hover:shadow-pink-300/50 transition-shadow">
                            <i class="ph ph-user-plus text-lg"></i>
                            Buat Akun
                        </button>
                    </div>

                </form>

                {{-- Divider --}}
                <div class="mt-6 pt-6 border-t border-pink-100 text-center">
                    <p class="text-sm text-gray-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-pink-600 font-semibold hover:text-pink-700 transition-colors hover:underline ml-1">
                            Masuk di sini
                            <i class="ph ph-arrow-right text-xs ml-0.5"></i>
                        </a>
                    </p>
                </div>

            </div>

            {{-- Back to home --}}
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-pink-600 transition-colors">
                    <i class="ph ph-arrow-left text-sm"></i>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
