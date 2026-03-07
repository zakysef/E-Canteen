<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Canteen — Sistem pre-order kantin sekolah. Pesan makan siang tanpa antri!">
    <title>E-Canteen — Pesan Makan Siang Tanpa Antri</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;0,14..32,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-hero { background: linear-gradient(135deg, #fdf2f8 0%, #fff1f2 50%, #ffffff 100%); }
        .gradient-cta  { background: linear-gradient(135deg, #db2777 0%, #f43f5e 100%); }
        .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(219,39,119,.18); }
        .blob { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
        @keyframes floatY { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        .float-1 { animation: floatY 5s ease-in-out infinite; }
        .float-2 { animation: floatY 5s ease-in-out infinite 2.5s; }
    </style>
</head>
<body class="antialiased text-gray-900 gradient-hero min-h-screen">

{{-- ================================================================ --}}
{{-- NAVBAR --}}
{{-- ================================================================ --}}
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-pink-100 shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 group flex-shrink-0">
            <div class="w-9 h-9 gradient-cta rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                <i class="ph ph-fork-knife text-white text-lg"></i>
            </div>
            <span class="text-xl font-bold text-gray-900">E-<span class="text-pink-600">Canteen</span></span>
        </a>

        {{-- Desktop nav links --}}
        <div class="hidden md:flex items-center gap-6">
            <a href="#cara-kerja" class="text-sm font-medium text-gray-500 hover:text-pink-600 transition-colors">Cara Kerja</a>
            @if($menus->count() > 0)
                <a href="#menu-preview" class="text-sm font-medium text-gray-500 hover:text-pink-600 transition-colors">Menu</a>
            @endif
        </div>

        {{-- Auth buttons --}}
        <div class="flex items-center gap-2 sm:gap-3">
            @auth
                @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('superadmin.dashboard') }}" class="inline-flex items-center gap-2 btn-primary text-sm">
                        <i class="ph ph-squares-four text-base"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 btn-primary text-sm">
                        <i class="ph ph-squares-four text-base"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 btn-primary text-sm">
                        <i class="ph ph-squares-four text-base"></i>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 btn-secondary text-sm">
                    <i class="ph ph-sign-in text-base"></i>
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 btn-primary text-sm">
                    <i class="ph ph-user-plus text-base"></i>
                    Daftar
                </a>
            @endauth
        </div>

    </nav>
</header>

{{-- ================================================================ --}}
{{-- HERO SECTION --}}
{{-- ================================================================ --}}
<section class="relative overflow-hidden pt-16 pb-24 lg:pt-24 lg:pb-32">

    {{-- Decorative blobs --}}
    <div class="absolute -top-10 -left-20 w-80 h-80 bg-pink-200/25 blob animate-pulse float-1 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 -right-16 w-72 h-72 bg-rose-200/20 blob animate-pulse float-2 pointer-events-none" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">

            {{-- Left — headline + CTAs --}}
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-pink-100 text-pink-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-6">
                    <i class="ph ph-lightning text-sm"></i>
                    Sistem Pre-Order Kantin Digital
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-black text-gray-900 leading-[1.1] tracking-tight mb-6">
                    Pesan Makan Siang<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-500">
                        Tanpa Antri!
                    </span>
                </h1>

                <p class="text-lg text-gray-500 leading-relaxed mb-10 max-w-lg mx-auto lg:mx-0">
                    Pre-order menu kantin favoritmu sebelum istirahat. Bayar dengan saldo digital, ambil tepat waktu — tidak perlu menunggu lagi.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 gradient-cta text-white font-bold px-8 py-4 rounded-2xl text-base hover:opacity-90 transition-all shadow-lg shadow-pink-300/40 hover:-translate-y-0.5">
                        <i class="ph ph-shopping-cart text-lg"></i>
                        Pre-Order Sekarang
                        <i class="ph ph-arrow-right text-base"></i>
                    </a>
                    <a href="#cara-kerja"
                       class="inline-flex items-center justify-center gap-2 btn-secondary font-bold px-8 py-4 rounded-2xl text-base">
                        <i class="ph ph-play-circle text-lg"></i>
                        Pelajari Cara Kerja
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="mt-10 flex flex-wrap items-center gap-5 justify-center lg:justify-start">
                    <div class="flex items-center gap-1.5 text-sm text-gray-400">
                        <i class="ph ph-shield-check text-green-500 text-base"></i>
                        Transaksi Aman
                    </div>
                    <div class="flex items-center gap-1.5 text-sm text-gray-400">
                        <i class="ph ph-clock text-pink-500 text-base"></i>
                        Hemat Waktu
                    </div>
                    <div class="flex items-center gap-1.5 text-sm text-gray-400">
                        <i class="ph ph-star text-yellow-400 text-base"></i>
                        Mudah Digunakan
                    </div>
                </div>
            </div>

            {{-- Right — 2×2 Feature Cards --}}
            <div class="grid grid-cols-2 gap-4">

                <div class="card p-5 card-hover">
                    <div class="w-11 h-11 bg-pink-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="ph ph-shopping-cart text-pink-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1.5">Pre-Order Mudah</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Pesan menu favoritmu kapan saja sebelum jam istirahat dimulai.</p>
                </div>

                <div class="card p-5 card-hover gradient-cta border-0 mt-4 sm:mt-6">
                    <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                        <i class="ph ph-wallet text-white text-xl"></i>
                    </div>
                    <h3 class="font-bold text-white text-sm mb-1.5">Saldo Digital</h3>
                    <p class="text-xs text-white/80 leading-relaxed">Top up saldo dan bayar tanpa uang tunai. Aman &amp; praktis.</p>
                </div>

                <div class="card p-5 card-hover" style="background: linear-gradient(135deg,#f43f5e,#db2777); border:0;">
                    <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                        <i class="ph ph-clock text-white text-xl"></i>
                    </div>
                    <h3 class="font-bold text-white text-sm mb-1.5">Pilih Waktu Ambil</h3>
                    <p class="text-xs text-white/80 leading-relaxed">Tentukan slot istirahat pertama atau kedua sesuai jadwalmu.</p>
                </div>

                <div class="card p-5 card-hover mt-4 sm:mt-6">
                    <div class="w-11 h-11 bg-pink-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="ph ph-check-circle text-pink-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1.5">Ambil Tepat Waktu</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Pesananmu sudah siap sebelum kamu datang ke kantin.</p>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ================================================================ --}}
{{-- STATS BAR --}}
{{-- ================================================================ --}}
<section class="gradient-cta py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center text-white">
            <div>
                <div class="text-4xl font-black mb-1.5">300+</div>
                <div class="flex items-center justify-center gap-1.5 text-pink-100 text-sm font-medium">
                    <i class="ph ph-users text-base"></i> Siswa Terdaftar
                </div>
            </div>
            <div>
                <div class="text-4xl font-black mb-1.5">15+</div>
                <div class="flex items-center justify-center gap-1.5 text-pink-100 text-sm font-medium">
                    <i class="ph ph-fork-knife text-base"></i> Pilihan Menu
                </div>
            </div>
            <div>
                <div class="text-4xl font-black mb-1.5">2</div>
                <div class="flex items-center justify-center gap-1.5 text-pink-100 text-sm font-medium">
                    <i class="ph ph-clock text-base"></i> Waktu Istirahat
                </div>
            </div>
            <div>
                <div class="text-4xl font-black mb-1.5">100%</div>
                <div class="flex items-center justify-center gap-1.5 text-pink-100 text-sm font-medium">
                    <i class="ph ph-shield-check text-base"></i> Transaksi Aman
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================ --}}
{{-- CARA KERJA SECTION --}}
{{-- ================================================================ --}}
<section id="cara-kerja" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-pink-50 text-pink-600 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
                <i class="ph ph-list-numbers text-sm"></i>
                Hanya 4 Langkah
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4">Cara Kerja E-Canteen</h2>
            <p class="text-gray-500 max-w-xl mx-auto text-base">Mudah, cepat, dan efisien. Makan siangmu tidak perlu menggangu waktu belajar.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">

            {{-- Connector line (lg only) --}}
            <div class="hidden lg:block absolute top-9 left-[calc(12.5%+2rem)] right-[calc(12.5%+2rem)] h-0.5 bg-gradient-to-r from-pink-200 via-pink-400 to-rose-300" aria-hidden="true"></div>

            @php
                $steps = [
                    ['icon' => 'ph-hand-coins',         'label' => 'Daftar &amp; Top Up Saldo',    'desc' => 'Buat akun dengan NIS kamu, lalu isi saldo dompet digitalmu melalui admin.'],
                    ['icon' => 'ph-fork-knife',          'label' => 'Pilih Menu dari Kantin',       'desc' => 'Jelajahi menu yang tersedia dan tambahkan pilihan favoritmu ke keranjang.'],
                    ['icon' => 'ph-clock',               'label' => 'Pilih Waktu Pengambilan',      'desc' => 'Tentukan slot istirahat pertama atau kedua untuk mengambil pesananmu.'],
                    ['icon' => 'ph-check-circle',        'label' => 'Ambil Pesanan',                'desc' => 'Tunjukkan kode pesanan ke penjual dan nikmati makananmu tanpa antri!'],
                ];
            @endphp

            @foreach($steps as $i => $step)
            <div class="relative z-10 text-center group">
                {{-- Step number badge --}}
                <div class="relative inline-block mb-5">
                    <div class="w-20 h-20 gradient-cta rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-pink-300/40 group-hover:scale-105 transition-transform duration-200">
                        <i class="ph {{ $step['icon'] }} text-white text-3xl"></i>
                    </div>
                    <span class="absolute -top-2 -right-2 w-6 h-6 bg-white border-2 border-pink-500 text-pink-600 text-xs font-black rounded-full flex items-center justify-center shadow">
                        {{ $i + 1 }}
                    </span>
                </div>
                <h3 class="font-bold text-gray-900 text-base mb-2">{!! $step['label'] !!}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach

        </div>

        <div class="text-center mt-14">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 gradient-cta text-white font-bold px-10 py-4 rounded-2xl text-base hover:opacity-90 transition-all shadow-lg shadow-pink-300/40 hover:-translate-y-0.5">
                <i class="ph ph-rocket-launch text-lg"></i>
                Mulai Sekarang — Gratis!
            </a>
        </div>

    </div>
</section>

{{-- ================================================================ --}}
{{-- MENU PREVIEW SECTION --}}
{{-- ================================================================ --}}
@if($menus->count() > 0)
<section id="menu-preview" class="py-20 gradient-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 bg-pink-100 text-pink-600 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
                <i class="ph ph-star text-sm"></i>
                Menu Unggulan
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4">Menu Tersedia Hari Ini</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Pilihan menu lezat dari kantin sekolah. Pesan sekarang sebelum kehabisan!</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($menus->take(8) as $menu)
            <div class="card card-hover overflow-hidden group">

                {{-- Image --}}
                <div class="relative h-44 overflow-hidden">
                    @if(!empty($menu->foto_url))
                        <img src="{{ $menu->foto_url }}"
                             alt="{{ $menu->nama }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full gradient-cta flex items-center justify-center">
                            <i class="ph ph-fork-knife text-white/40 text-5xl"></i>
                        </div>
                    @endif
                    {{-- Price badge --}}
                    <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm text-pink-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-md">
                        Rp&nbsp;{{ number_format($menu->harga, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-sm mb-1 truncate">{{ $menu->nama }}</h3>
                    <div class="flex items-center gap-1.5 text-xs text-gray-400">
                        <i class="ph ph-storefront text-pink-400 text-sm flex-shrink-0"></i>
                        <span class="truncate">{{ $menu->seller->nama_toko ?? ($menu->seller->name ?? 'Kantin Sekolah') }}</span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-pink-50 flex items-center justify-between">
                        <span class="flex items-center gap-1 text-xs text-green-600 font-medium">
                            <i class="ph ph-check-circle text-sm"></i> Tersedia
                        </span>
                        <span class="flex items-center gap-1 text-xs text-gray-400">
                            <i class="ph ph-star-fill text-yellow-400 text-sm"></i> Favorit
                        </span>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 btn-secondary font-semibold px-8 py-3.5 rounded-2xl text-sm">
                <i class="ph ph-list text-base"></i>
                Lihat Semua Menu — Daftar Dahulu
                <i class="ph ph-arrow-right text-base"></i>
            </a>
        </div>

    </div>
</section>
@endif

{{-- ================================================================ --}}
{{-- FOOTER --}}
{{-- ================================================================ --}}
<footer class="bg-gray-900 text-gray-400 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">

            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 gradient-cta rounded-xl flex items-center justify-center">
                    <i class="ph ph-fork-knife text-white text-sm"></i>
                </div>
                <span class="text-white font-bold text-base">E-<span class="text-pink-400">Canteen</span></span>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-5 text-sm">
                <a href="#cara-kerja" class="hover:text-pink-400 transition-colors">Cara Kerja</a>
                @if($menus->count() > 0)
                    <a href="#menu-preview" class="hover:text-pink-400 transition-colors">Menu</a>
                @endif
                <a href="{{ route('login') }}" class="hover:text-pink-400 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="hover:text-pink-400 transition-colors">Daftar</a>
            </div>

            <p class="text-sm text-gray-600 flex items-center gap-1.5">
                <i class="ph ph-copyright text-sm"></i>
                {{ date('Y') }} E-Canteen. Hak cipta dilindungi.
            </p>

        </div>
    </div>
</footer>

</body>
</html>
