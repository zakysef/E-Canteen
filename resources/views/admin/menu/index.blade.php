@extends('layouts.admin')

@section('title', 'Kelola Menu')
@section('page-title', 'Kelola Menu')

@section('content-inner')

{{-- Header bar --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    {{-- Filter by status --}}
    <div class="flex items-center gap-2">
        <i class="ph ph-funnel text-gray-400 text-lg"></i>
        @php $statusFilter = request('status', ''); @endphp
        <a href="{{ route('admin.menu.index') }}"
           class="btn-sm px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
                  {{ $statusFilter === '' ? 'bg-orange-600 text-white' : 'bg-white border border-pink-200 text-gray-600 hover:bg-pink-50' }}">
            Semua
        </a>
        <a href="{{ route('admin.menu.index', ['status' => 'tersedia']) }}"
           class="btn-sm px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
                  {{ $statusFilter === 'tersedia' ? 'bg-green-600 text-white' : 'bg-white border border-pink-200 text-gray-600 hover:bg-pink-50' }}">
            <i class="ph ph-toggle-right"></i> Tersedia
        </a>
        <a href="{{ route('admin.menu.index', ['status' => 'habis']) }}"
           class="btn-sm px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
                  {{ $statusFilter === 'habis' ? 'bg-red-500 text-white' : 'bg-white border border-pink-200 text-gray-600 hover:bg-pink-50' }}">
            <i class="ph ph-toggle-left"></i> Habis
        </a>
    </div>

    <div class="flex items-center gap-3">
        @if($menus instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <p class="text-sm text-gray-400">{{ $menus->total() }} menu</p>
        @endif
        <a href="{{ route('admin.menu.create') }}" class="btn-primary inline-flex items-center gap-2">
            <i class="ph ph-plus"></i> Tambah Menu
        </a>
    </div>

</div>

{{-- Menu Grid --}}
@if($menus->count() > 0)
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
    @foreach($menus as $menu)
    <div class="card overflow-hidden flex flex-col group">

        {{-- Foto --}}
        <div class="relative h-40 bg-gradient-to-br from-orange-50 to-amber-50 overflow-hidden">
            @if($menu->foto)
                <img src="{{ $menu->foto_url }}" alt="{{ $menu->nama }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="ph ph-image text-5xl text-orange-200"></i>
                </div>
            @endif

            {{-- Status badge overlay --}}
            @if($menu->status === 'tersedia')
                <span class="absolute top-2 right-2 badge bg-green-100 text-green-700">
                    <i class="ph ph-toggle-right text-[11px]"></i> Tersedia
                </span>
            @else
                <span class="absolute top-2 right-2 badge bg-red-100 text-red-700">
                    <i class="ph ph-toggle-left text-[11px]"></i> Habis
                </span>
            @endif

            {{-- Kategori badge overlay --}}
            @php
                $kBg = match($menu->kategori) {
                    'makanan'  => 'bg-orange-600',
                    'minuman'  => 'bg-blue-600',
                    'snack'    => 'bg-purple-600',
                    default    => 'bg-gray-600',
                };
            @endphp
            <span class="absolute top-2 left-2 badge {{ $kBg }} text-white capitalize">
                {{ $menu->kategori }}
            </span>
        </div>

        {{-- Info --}}
        <div class="p-4 flex flex-col flex-1">
            <h3 class="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 mb-1">{{ $menu->nama }}</h3>
            @if($menu->deskripsi)
            <p class="text-xs text-gray-400 line-clamp-2 mb-2">{{ $menu->deskripsi }}</p>
            @endif
            <p class="text-base font-bold text-orange-600 mt-auto mb-3">
                Rp {{ number_format($menu->harga, 0, ',', '.') }}
            </p>

            {{-- Action buttons --}}
            <div class="flex gap-2">
                {{-- Toggle Status --}}
                <form method="POST" action="{{ route('admin.menu.toggle', $menu) }}" class="flex-1">
                    @csrf @method('PATCH')
                    @if($menu->status === 'tersedia')
                        <button type="submit"
                                class="w-full btn-sm text-xs font-semibold bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition-colors rounded-lg py-1.5 inline-flex items-center justify-center gap-1">
                            <i class="ph ph-toggle-left"></i> Set Habis
                        </button>
                    @else
                        <button type="submit"
                                class="w-full btn-sm text-xs font-semibold bg-green-100 text-green-700 hover:bg-green-200 transition-colors rounded-lg py-1.5 inline-flex items-center justify-center gap-1">
                            <i class="ph ph-toggle-right"></i> Tersedia
                        </button>
                    @endif
                </form>

                {{-- Edit --}}
                <a href="{{ route('admin.menu.edit', $menu) }}"
                   class="btn-sm text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors rounded-lg py-1.5 px-3 inline-flex items-center gap-1">
                    <i class="ph ph-pencil-simple"></i>
                </a>

                {{-- Delete --}}
                <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}"
                      onsubmit="return confirm('Hapus menu {{ addslashes($menu->nama) }}? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn-sm text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition-colors rounded-lg py-1.5 px-3 inline-flex items-center gap-1">
                        <i class="ph ph-trash"></i>
                    </button>
                </form>
            </div>
        </div>

    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($menus instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="mt-6">{{ $menus->withQueryString()->links() }}</div>
@endif

@else
{{-- Empty state --}}
<div class="card py-20 flex flex-col items-center justify-center text-center">
    <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center mb-5">
        <i class="ph ph-fork-knife text-4xl text-orange-400"></i>
    </div>
    <h3 class="font-semibold text-gray-700 text-lg mb-2">Belum ada menu</h3>
    <p class="text-gray-400 text-sm mb-6">Tambahkan menu pertama Anda untuk mulai menerima pesanan.</p>
    <a href="{{ route('admin.menu.create') }}" class="btn-primary inline-flex items-center gap-2">
        <i class="ph ph-plus"></i> Tambah Menu Pertama
    </a>
</div>
@endif

@endsection
