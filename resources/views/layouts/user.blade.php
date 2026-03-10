@extends('layouts.base')

@section('content')
<div class="flex h-full bg-pink-50"
     x-data="{
        expanded: localStorage.getItem('user_sidebar') !== 'false',
        mobileOpen: false,
        toggle() { this.expanded = !this.expanded; localStorage.setItem('user_sidebar', this.expanded) }
     }"
     x-cloak>

    {{-- Mobile backdrop --}}
    <div x-show="mobileOpen" @click="mobileOpen = false"
         class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150" x-transition:leave-end="opacity-0"></div>

    {{-- Sidebar --}}
    <aside :class="[expanded ? 'w-64' : 'w-[70px]', mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
           class="fixed lg:relative inset-y-0 left-0 z-30 flex flex-col bg-gradient-to-b from-pink-700 to-rose-500 text-white transition-all duration-300 ease-in-out overflow-hidden shrink-0 h-full select-none">

        {{-- Brand --}}
        <div class="flex items-center h-16 px-4 gap-3 border-b border-white/10 shrink-0">
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <i class="ph ph-fork-knife text-lg"></i>
            </div>
            <span x-show="expanded" x-transition:enter="transition-opacity duration-200 delay-100" x-transition:enter-start="opacity-0"
                  class="font-bold text-base whitespace-nowrap">E-Canteen</span>
        </div>

        {{-- User greeting + saldo --}}
        <div x-show="expanded" class="px-4 pt-4 pb-2">
            <span class="text-xs font-semibold text-pink-200 uppercase tracking-widest">
                {{ auth()->user()->kelas ? 'Jurusan ' . auth()->user()->kelas : 'Siswa / Guru' }}
            </span>
            <p class="text-sm text-white/90 font-medium mt-0.5 truncate">{{ auth()->user()->name }}</p>
        </div>

        {{-- Saldo badge --}}
        <div x-show="expanded" class="mx-3 mb-3 bg-white/15 rounded-xl px-3 py-2.5">
            <p class="text-[10px] text-pink-200 font-medium mb-0.5">Saldo Anda</p>
            <p class="text-sm font-bold text-white">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</p>
        </div>

        {{-- Nav items --}}
        <nav class="flex-1 py-2 overflow-y-auto overflow-x-hidden space-y-0.5">
            @php
            $nav = [
                ['route' => 'user.dashboard',    'icon' => 'ph-squares-four',      'label' => 'Beranda'],
                ['route' => 'user.catalog',      'icon' => 'ph-shopping-cart',     'label' => 'Pre-Order Menu'],
                ['route' => 'user.orders',       'icon' => 'ph-receipt',           'label' => 'Pesanan Saya'],
                ['route' => 'user.saldo',        'icon' => 'ph-wallet',            'label' => 'Saldo & Transaksi'],
                ['route' => 'user.saldo.topup',  'icon' => 'ph-plus-circle',       'label' => 'Top Up Saldo'],
                ['route' => 'user.settings',     'icon' => 'ph-gear-six',          'label' => 'Pengaturan'],
            ];
            @endphp
            @foreach($nav as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               :title="!expanded ? '{{ $item['label'] }}' : ''"
               class="nav-item {{ $active ? 'active bg-white/25' : 'hover:bg-white/10' }}">
                <i class="ph {{ $item['icon'] }} text-xl w-5 shrink-0"></i>
                <span x-show="expanded" class="whitespace-nowrap text-sm font-medium">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- User info --}}
        <div class="border-t border-white/10 p-3">
            <div class="flex items-center gap-3 px-1">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div x-show="expanded" class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-pink-200">{{ auth()->user()->identifier ? 'NIS: ' . auth()->user()->identifier : 'Siswa/Guru' }}</p>
                </div>
            </div>
        </div>

        {{-- Desktop collapse button --}}
        <button @click="toggle()" class="hidden lg:flex items-center justify-center h-11 border-t border-white/10 hover:bg-white/10 transition-colors shrink-0">
            <i :class="expanded ? 'ph-arrow-line-left' : 'ph-arrow-line-right'" class="ph text-lg"></i>
        </button>
    </aside>

    {{-- Main area --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-pink-100 flex items-center px-4 lg:px-6 gap-3 shrink-0 shadow-sm shadow-pink-100/50">
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-pink-50 hover:text-pink-600 transition-colors">
                <i class="ph ph-list text-xl"></i>
            </button>
            <div class="flex-1">
                <h1 class="text-base font-semibold text-gray-800 leading-tight">@yield('page-title', 'Beranda')</h1>
                @hasSection('page-subtitle')
                <p class="text-xs text-gray-500 mt-0.5">@yield('page-subtitle')</p>
                @endif
            </div>
            <a href="{{ route('user.saldo.topup') }}" class="hidden sm:flex items-center gap-1.5 bg-pink-50 border border-pink-200 text-pink-700 hover:bg-pink-100 transition-colors rounded-xl px-3 py-1.5">
                <i class="ph ph-wallet text-base"></i>
                <span class="text-xs font-semibold">Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}</span>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Keluar">
                        <i class="ph ph-sign-out text-xl"></i>
                    </button>
                </form>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield('content-inner')
        </main>
    </div>
</div>
@endsection
