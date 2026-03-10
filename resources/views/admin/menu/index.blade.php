@extends('layouts.admin')

@section('title', 'Kelola Menu')
@section('page-title', 'Kelola Menu')

@section('content-inner')

{{-- Flash messages --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
    <i class="ph ph-check-circle text-lg shrink-0"></i>
    {{ session('success') }}
</div>
@endif

{{-- Header bar --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    {{-- Filter by status --}}
    <div class="flex items-center gap-2">
        <i class="ph ph-funnel text-pink-400 text-lg"></i>
        @php $statusFilter = request('status', ''); @endphp
        <a href="{{ route('admin.menu.index') }}"
           class="btn-sm px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors
                  {{ $statusFilter === '' ? 'bg-pink-600 text-white' : 'bg-white border border-pink-200 text-gray-600 hover:bg-pink-50' }}">
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
        <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold transition-colors shadow-sm">
            <i class="ph ph-plus"></i> Tambah Menu
        </a>
    </div>

</div>

{{-- Menu Grid --}}
@if($menus->count() > 0)
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
    @foreach($menus as $menu)
    <div class="bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:border-pink-200 transition-all"
         x-data="{ editingStok: false, stokVal: {{ $menu->stok }} }">

        {{-- Foto --}}
        <div class="relative h-40 bg-gradient-to-br from-pink-50 to-rose-50 overflow-hidden">
            @if($menu->foto)
                <img src="{{ $menu->foto_url }}" alt="{{ $menu->nama }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="ph ph-fork-knife text-5xl text-pink-200"></i>
                </div>
            @endif

            {{-- Status badge --}}
            @if($menu->status === 'tersedia' && $menu->stok > 0)
                <span class="absolute top-2 right-2 inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700 border border-green-200">
                    <i class="ph ph-circle-wavy-check text-xs"></i> Tersedia
                </span>
            @else
                <span class="absolute top-2 right-2 inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 border border-red-200">
                    <i class="ph ph-x-circle text-xs"></i> Habis
                </span>
            @endif

            {{-- Kategori badge --}}
            @php
                $kBg = match($menu->kategori) {
                    'makanan'  => 'bg-amber-500',
                    'minuman'  => 'bg-blue-500',
                    'snack'    => 'bg-purple-500',
                    default    => 'bg-gray-500',
                };
            @endphp
            <span class="absolute top-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $kBg }} text-white capitalize">
                {{ $menu->kategori }}
            </span>
        </div>

        {{-- Info --}}
        <div class="p-4 flex flex-col flex-1">
            <h3 class="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 mb-1">{{ $menu->nama }}</h3>
            @if($menu->deskripsi)
            <p class="text-xs text-gray-400 line-clamp-2 mb-2">{{ $menu->deskripsi }}</p>
            @endif
            <p class="text-base font-bold text-pink-600 mb-3">
                Rp {{ number_format($menu->harga, 0, ',', '.') }}
            </p>

            {{-- Stok display + inline update --}}
            <div class="mb-3">
                <div x-show="!editingStok" class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <i class="ph ph-stack text-pink-400 text-sm"></i>
                        <span class="text-xs font-semibold {{ $menu->stok > 5 ? 'text-gray-700' : ($menu->stok > 0 ? 'text-amber-600' : 'text-red-600') }}">
                            Stok: {{ $menu->stok }} porsi
                            @if($menu->stok > 0 && $menu->stok <= 5)
                                <span class="text-amber-500">(menipis)</span>
                            @elseif($menu->stok === 0)
                                <span class="text-red-500">(habis)</span>
                            @endif
                        </span>
                    </div>
                    <button @click="editingStok = true; $nextTick(() => $refs.stokInput.focus())"
                            class="text-[10px] text-pink-500 hover:text-pink-700 font-semibold hover:underline">
                        Isi Ulang
                    </button>
                </div>

                {{-- Inline stok update form --}}
                <form x-show="editingStok" method="POST" action="{{ route('admin.menu.stok', $menu) }}"
                      class="flex items-center gap-2" x-cloak>
                    @csrf @method('PATCH')
                    <input type="number" name="stok" x-ref="stokInput"
                           x-model="stokVal"
                           min="0" max="999"
                           class="w-20 text-sm border border-pink-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-pink-300 text-center font-semibold">
                    <button type="submit"
                            class="text-[10px] font-bold px-2 py-1 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                        Simpan
                    </button>
                    <button type="button" @click="editingStok = false; stokVal = {{ $menu->stok }}"
                            class="text-[10px] text-gray-400 hover:text-gray-600 font-semibold">
                        Batal
                    </button>
                </form>
            </div>

            {{-- Action buttons --}}
            <div class="flex gap-2 mt-auto">
                {{-- Set Habis (only when tersedia) --}}
                @if($menu->status === 'tersedia' && $menu->stok > 0)
                <form method="POST" action="{{ route('admin.menu.toggle', $menu) }}" class="flex-1">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full text-[11px] font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition-colors rounded-xl py-1.5 inline-flex items-center justify-center gap-1">
                        <i class="ph ph-minus-circle"></i> Set Habis
                    </button>
                </form>
                @else
                <div class="flex-1">
                    <span class="w-full text-[11px] font-semibold bg-gray-50 text-gray-400 border border-gray-100 rounded-xl py-1.5 inline-flex items-center justify-center gap-1 cursor-default">
                        <i class="ph ph-x-circle"></i> Stok Habis
                    </span>
                </div>
                @endif

                {{-- Edit --}}
                <a href="{{ route('admin.menu.edit', $menu) }}"
                   class="text-[11px] font-semibold bg-pink-50 text-pink-600 hover:bg-pink-100 border border-pink-200 transition-colors rounded-xl py-1.5 px-3 inline-flex items-center gap-1">
                    <i class="ph ph-pencil-simple"></i>
                </a>

                {{-- Delete --}}
                <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}"
                      onsubmit="return confirm('Hapus menu {{ addslashes($menu->nama) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-[11px] font-semibold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors rounded-xl py-1.5 px-3 inline-flex items-center gap-1">
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
<div class="bg-white rounded-2xl border border-pink-100 py-20 flex flex-col items-center justify-center text-center shadow-sm">
    <div class="w-20 h-20 rounded-full bg-pink-100 flex items-center justify-center mb-5">
        <i class="ph ph-fork-knife text-4xl text-pink-400"></i>
    </div>
    <h3 class="font-semibold text-gray-700 text-lg mb-2">Belum ada menu</h3>
    <p class="text-gray-400 text-sm mb-6">Tambahkan menu pertama Anda untuk mulai menerima pesanan.</p>
    <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold transition-colors">
        <i class="ph ph-plus"></i> Tambah Menu Pertama
    </a>
</div>
@endif

@endsection
