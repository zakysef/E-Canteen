@extends('layouts.base')

@section('title', 'Daftar')

@section('content')

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
    <div class="relative z-10 min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg">

            {{-- Card --}}
            <div class="card shadow-2xl shadow-black/25 overflow-hidden">

                {{-- Card top: branding strip --}}
                <div class="px-5 pt-4 pb-3 flex items-center gap-3 border-b border-pink-100">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg shadow-pink-300/50 shrink-0">
                        <i class="ph ph-fork-knife text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="font-black text-gray-900 leading-tight">E-Canteen</p>
                        <p class="text-xs text-gray-400">Sistem Pre-Order Kantin Digital</p>
                    </div>
                </div>

                {{-- Card body --}}
                <div class="px-5 py-3">

                    {{-- Header --}}
                    <div class="mb-2">
                        <h1 class="text-xl font-black text-gray-900 mb-0.5">Buat Akun Baru</h1>
                        <p class="text-sm text-gray-500">Isi formulir di bawah untuk mendaftarkan akunmu.</p>
                    </div>

                    {{-- Google register button --}}
                    <a href="{{ route('auth.google') }}"
                       class="flex items-center justify-center gap-3 w-full py-2 px-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm shadow-sm hover:shadow transition-all mb-2">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Daftar dengan Google
                    </a>

                    {{-- Divider --}}
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-xs text-gray-400 font-medium">atau daftar dengan email</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('register.store') }}" class="space-y-2">
                        @csrf

                        {{-- Row 1: Nama + Email --}}
                        <div class="grid grid-cols-2 gap-3">
                            {{-- 1. Nama Lengkap --}}
                            <div>
                                <label for="name" class="form-label text-xs">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-user text-gray-400 text-sm"></i>
                                    </span>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                           placeholder="Nama lengkap"
                                           class="form-input pl-8 py-2 text-sm @error('name') error @enderror">
                                </div>
                                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            {{-- 2. Email --}}
                            <div>
                                <label for="email" class="form-label text-xs">Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-envelope text-gray-400 text-sm"></i>
                                    </span>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           placeholder="email@sekolah.com"
                                           class="form-input pl-8 py-2 text-sm @error('email') error @enderror">
                                </div>
                                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Row 2: NIS + Jurusan --}}
                        <div class="grid grid-cols-2 gap-3">
                            {{-- 3. NIS --}}
                            <div>
                                <label for="identifier" class="form-label text-xs">NIS <span class="text-gray-400 font-normal">(5 digit)</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-identification-card text-gray-400 text-sm"></i>
                                    </span>
                                    <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}"
                                           maxlength="5" inputmode="numeric" pattern="[0-9]{5}" placeholder="12345"
                                           class="form-input pl-8 py-2 text-sm @error('identifier') error @enderror">
                                </div>
                                @error('identifier')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            {{-- 4. Jurusan --}}
                            <div>
                                <label for="kelas" class="form-label text-xs">Jurusan</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-graduation-cap text-gray-400 text-sm"></i>
                                    </span>
                                    <select id="kelas" name="kelas"
                                            class="form-input pl-8 pr-8 py-2 text-sm appearance-none @error('kelas') error @enderror">
                                        <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>Pilih Jurusan</option>
                                        @foreach(['RPL' => 'RPL', 'DKV' => 'DKV', 'AK' => 'Akuntansi', 'MP' => 'Manajemen', 'BR' => 'Bisnis Ritel'] as $v => $l)
                                            <option value="{{ $v }}" {{ old('kelas') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="ph ph-caret-down text-gray-400 text-xs"></i>
                                    </span>
                                </div>
                                @error('kelas')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Row 3: Telepon (full) --}}
                        <div>
                            <label for="phone" class="form-label text-xs">
                                No. Telepon <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-phone text-gray-400 text-sm"></i>
                                </span>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       placeholder="08xx-xxxx-xxxx"
                                       class="form-input pl-8 py-2 text-sm @error('phone') error @enderror">
                            </div>
                            @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        {{-- Row 4: Password + Konfirmasi --}}
                        <div class="grid grid-cols-2 gap-3">
                            {{-- 5. Password --}}
                            <div>
                                <label for="password" class="form-label text-xs">Kata Sandi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-lock text-gray-400 text-sm"></i>
                                    </span>
                                    <input :type="showPassword ? 'text' : 'password'"
                                           id="password" name="password" required
                                           placeholder="Min. 8 karakter"
                                           class="form-input pl-8 pr-9 py-2 text-sm @error('password') error @enderror">
                                    <button type="button" @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                        <i :class="showPassword ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-sm"></i>
                                    </button>
                                </div>
                                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            {{-- 6. Konfirmasi Password --}}
                            <div>
                                <label for="password_confirmation" class="form-label text-xs">Konfirmasi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-lock text-gray-400 text-sm"></i>
                                    </span>
                                    <input :type="showConfirm ? 'text' : 'password'"
                                           id="password_confirmation" name="password_confirmation" required
                                           placeholder="Ulangi sandi"
                                           class="form-input pl-8 pr-9 py-2 text-sm @error('password_confirmation') error @enderror">
                                    <button type="button" @click="showConfirm = !showConfirm"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                        <i :class="showConfirm ? 'ph ph-eye-slash' : 'ph ph-eye'" class="text-sm"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="btn-primary w-full py-2.5 text-sm flex items-center justify-center gap-2 rounded-xl shadow-md shadow-pink-200/50">
                            <i class="ph ph-user-plus text-sm"></i>
                            Buat Akun
                        </button>

                    </form>

                    {{-- Footer --}}
                    <div class="mt-3 pt-3 border-t border-pink-100 text-center space-y-2">
                        <p class="text-sm text-gray-500">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-pink-600 font-semibold hover:text-pink-700 transition-colors hover:underline ml-1">
                                Masuk di sini <i class="ph ph-arrow-right text-xs"></i>
                            </a>
                        </p>
                        <a href="{{ url('/') }}"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-rose-600 transition-colors border border-gray-200 hover:border-rose-300 hover:bg-rose-50 px-4 py-2 rounded-xl w-full justify-center">
                            <i class="ph ph-arrow-left text-sm"></i>
                            Kembali ke Beranda
                        </a>
                    </div>

                </div>{{-- /card body --}}
            </div>{{-- /card --}}

        </div>
    </div>

</div>
@endsection
