@extends('layouts.base')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex"
     x-data="{ showPassword: false }">

    {{-- ============================================================ --}}
    {{-- LEFT PANEL — decorative (hidden on mobile) --}}
    {{-- ============================================================ --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col items-center justify-center p-12"
         style="background: linear-gradient(135deg, #db2777 0%, #f43f5e 100%);">

        {{-- Background pattern dots --}}
        <div class="absolute inset-0 opacity-10" aria-hidden="true"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

        {{-- Decorative blobs --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl" aria-hidden="true"></div>

        {{-- Content --}}
        <div class="relative z-10 text-white text-center max-w-sm">

            {{-- Logo --}}
            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl">
                <i class="ph ph-fork-knife text-white text-4xl"></i>
            </div>

            <h2 class="text-4xl font-black mb-3 leading-tight">E-Canteen</h2>
            <p class="text-pink-100 text-base font-medium mb-2">SMK Negeri / Swasta</p>
            <p class="text-pink-200 text-sm leading-relaxed mb-10">
                Sistem pre-order kantin digital. Pesan makanan dari kelas, bayar cashless, ambil tanpa antri.
            </p>

            {{-- Feature list --}}
            <ul class="space-y-4 text-left">
                @foreach([
                    ['ph-shopping-cart',   'Pre-order sebelum jam istirahat'],
                    ['ph-wallet',          'Saldo digital — bayar cashless'],
                    ['ph-clock',           'Pilih waktu pengambilan fleksibel'],
                    ['ph-check-circle',    'Pesanan siap tepat waktu'],
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

        {{-- Bottom credit --}}
        <p class="absolute bottom-6 text-pink-200/60 text-xs">
            &copy; {{ date('Y') }} E-Canteen. Hak cipta dilindungi.
        </p>
    </div>

    {{-- ============================================================ --}}
    {{-- RIGHT PANEL — login form --}}
    {{-- ============================================================ --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-10 bg-gradient-to-br from-pink-50 via-white to-rose-50 min-h-screen">

        <div class="w-full max-w-md">

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
                    <h1 class="text-2xl font-black text-gray-900 mb-1.5">Selamat Datang!</h1>
                    <p class="text-sm text-gray-500">Masuk ke akun E-Canteen untuk mulai memesan.</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="form-label">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-envelope text-gray-400 text-base"></i>
                            </span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
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

                    {{-- Password --}}
                    <div>
                        <label for="password" class="form-label">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-lock text-gray-400 text-base"></i>
                            </span>
                            <input :type="showPassword ? 'text' : 'password'"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Masukkan kata sandi"
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

                    {{-- Remember me + Forgot password --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox"
                                   name="remember"
                                   class="w-4 h-4 rounded border-pink-300 text-pink-600 focus:ring-pink-400 focus:ring-2 cursor-pointer">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-pink-600 font-medium hover:text-pink-700 transition-colors hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="btn-primary w-full py-3 text-base flex items-center justify-center gap-2 rounded-xl shadow-md shadow-pink-200/50 hover:shadow-pink-300/50 transition-shadow">
                        <i class="ph ph-sign-in text-lg"></i>
                        Masuk
                    </button>

                </form>

                {{-- Divider --}}
                <div class="mt-6 pt-6 border-t border-pink-100 text-center">
                    <p class="text-sm text-gray-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-pink-600 font-semibold hover:text-pink-700 transition-colors hover:underline ml-1">
                            Daftar di sini
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
