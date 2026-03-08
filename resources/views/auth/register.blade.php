@extends('layouts.base')

@section('title', 'Daftar')

@section('content')

{{-- Outer: fixed+scroll agar gradient selalu penuh, tidak ada gap putih --}}
<div class="fixed inset-0 overflow-y-auto"
     style="background: linear-gradient(135deg, #be185d 0%, #db2777 50%, #f43f5e 100%);"
     x-data="{ showPassword: false, showConfirm: false }">

    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none" aria-hidden="true"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

    {{-- Decorative blobs --}}
    <div class="fixed -top-24 -left-24 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="fixed -bottom-24 -right-24 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    {{-- Content wrapper --}}
    <div class="relative z-10 flex items-center justify-center p-4 sm:p-6 py-10">
        <div class="w-full max-w-md">

            {{-- Tombol Kembali ke Beranda — di atas card --}}
            <div class="mb-5">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-white/80 hover:text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm px-3.5 py-2 rounded-xl transition-all shadow-sm">
                    <i class="ph ph-arrow-left text-sm"></i>
                    Kembali ke Beranda
                </a>
            </div>

            {{-- Logo & branding --}}
            <div class="flex flex-col items-center mb-7 text-center">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 shadow-xl ring-1 ring-white/30">
                    <i class="ph ph-fork-knife text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight">E-Canteen</h2>
                <p class="text-pink-100 text-sm mt-1">Sistem Pre-Order Kantin Digital</p>
            </div>

            {{-- Card --}}
            <div class="card p-8 sm:p-10 shadow-2xl shadow-black/20">

                {{-- Header --}}
                <div class="mb-7">
                    <h1 class="text-2xl font-black text-gray-900 mb-1.5">Buat Akun Baru</h1>
                    <p class="text-sm text-gray-500">Isi formulir di bawah untuk mendaftarkan akunmu.</p>
                </div>

                {{-- Google register button --}}
                <a href="{{ route('auth.google') }}"
                   class="flex items-center justify-center gap-3 w-full py-2.5 px-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm shadow-sm hover:shadow transition-all mb-5">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Daftar dengan Google
                </a>

                {{-- Divider --}}
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium">atau daftar dengan email</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
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
                        <label for="identifier" class="form-label">Nomor Induk Siswa (NIS)</label>
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

                    {{-- 5. No. Telepon (opsional) --}}
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

                {{-- Footer link --}}
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

        </div>
    </div>

</div>
@endsection
