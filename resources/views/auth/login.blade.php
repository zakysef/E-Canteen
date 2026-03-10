@extends('layouts.base')

@section('title', 'Masuk')

@section('content')

<div class="fixed inset-0 flex items-center justify-center"
     style="background: linear-gradient(135deg, #be185d 0%, #db2777 50%, #f43f5e 100%);"
     x-data="{ showPassword: false }">

    {{-- Background pattern dots --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none" aria-hidden="true"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

    {{-- Decorative blobs --}}
    <div class="fixed -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="fixed -bottom-20 -right-20 w-80 h-80 bg-rose-300/20 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    {{-- Content wrapper --}}
    <div class="relative z-10 w-full max-w-sm px-4">

        {{-- Card --}}
        <div class="card shadow-2xl shadow-black/25 overflow-hidden">

            {{-- Card top: branding strip --}}
            <div class="px-6 pt-5 pb-4 flex items-center gap-3 border-b border-pink-100">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg shadow-pink-300/50 shrink-0">
                    <i class="ph ph-fork-knife text-white text-xl"></i>
                </div>
                <div>
                    <p class="font-black text-gray-900 leading-tight">E-Canteen</p>
                    <p class="text-xs text-gray-400">Sistem Pre-Order Kantin Digital</p>
                </div>
            </div>

            {{-- Card body --}}
            <div class="px-6 py-5">

                {{-- Header --}}
                <div class="mb-5">
                    <h1 class="text-xl font-black text-gray-900 mb-1">Selamat Datang!</h1>
                    <p class="text-sm text-gray-500">Masuk ke akun E-Canteen untuk mulai memesan.</p>
                </div>

                {{-- Google login button --}}
                <a href="{{ route('auth.google') }}"
                   class="flex items-center justify-center gap-3 w-full py-2 px-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm shadow-sm hover:shadow transition-all mb-4">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk dengan Google
                </a>

                {{-- Divider --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium">atau masuk dengan email</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
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
                        <label for="password" class="form-label">Kata Sandi</label>
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

                {{-- Footer link --}}
                <div class="mt-4 pt-4 border-t border-pink-100 text-center space-y-2">
                    <p class="text-sm text-gray-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-pink-600 font-semibold hover:text-pink-700 transition-colors hover:underline ml-1">
                            Daftar di sini <i class="ph ph-arrow-right text-xs"></i>
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
@endsection
