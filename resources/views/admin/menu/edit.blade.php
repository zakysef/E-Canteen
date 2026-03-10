@extends('layouts.admin')

@section('title', 'Edit Menu — ' . $menu->nama)
@section('page-title', 'Edit Menu')

@section('content-inner')

<div class="max-w-2xl">

    {{-- Back link --}}
    <a href="{{ route('admin.menu.index') }}"
       class="inline-flex items-center gap-2 text-sm text-pink-600 hover:text-pink-700 font-medium mb-6">
        <i class="ph ph-arrow-left"></i> Kembali ke Daftar Menu
    </a>

    <div class="card p-6 lg:p-8"
         x-data="{
             preview: {{ $menu->foto ? json_encode($menu->foto_url) : 'null' }},
             updatePreview(val) {
                 this.preview = val.trim() || null;
             }
         }">

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-pink-100">
            <h2 class="text-base font-semibold text-gray-800">
                <i class="ph ph-pencil-simple text-pink-500 mr-1"></i> Edit: {{ $menu->nama }}
            </h2>
            @php
                $kBg = match($menu->kategori) {
                    'makanan' => 'bg-orange-100 text-orange-700',
                    'minuman' => 'bg-blue-100 text-blue-700',
                    'snack'   => 'bg-purple-100 text-purple-700',
                    default   => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <span class="badge {{ $kBg }} capitalize">{{ $menu->kategori }}</span>
        </div>

        <form method="POST" action="{{ route('admin.menu.update', $menu) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Menu --}}
            <div>
                <label for="nama" class="form-label">
                    Nama Menu <span class="text-red-400">*</span>
                </label>
                <input type="text" id="nama" name="nama"
                       value="{{ old('nama', $menu->nama) }}" required
                       class="form-input @error('nama') error @enderror"
                       placeholder="Nama menu">
                @error('nama')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kategori & Harga --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="kategori" class="form-label">
                        Kategori <span class="text-red-400">*</span>
                    </label>
                    <select id="kategori" name="kategori" required class="form-input">
                        <option value="makanan" {{ old('kategori', $menu->kategori) === 'makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="minuman" {{ old('kategori', $menu->kategori) === 'minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="snack"   {{ old('kategori', $menu->kategori) === 'snack'   ? 'selected' : '' }}>Snack</option>
                    </select>
                    @error('kategori')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="ph ph-warning-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="harga" class="form-label">
                        Harga <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium pointer-events-none">Rp</span>
                        <input type="number" id="harga" name="harga"
                               value="{{ old('harga', $menu->harga) }}"
                               min="0" step="500" required
                               class="form-input pl-10 @error('harga') error @enderror"
                               placeholder="10000">
                    </div>
                    @error('harga')
                        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                            <i class="ph ph-warning-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          class="form-input resize-none"
                          placeholder="Deskripsi singkat menu (opsional)...">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Foto Menu --}}
            <div>
                <label class="form-label">
                    <i class="ph ph-image mr-1"></i> Foto Menu (URL)
                </label>

                {{-- Preview --}}
                <div class="mb-3">
                    <template x-if="preview">
                        <img :src="preview" alt="Preview foto"
                             class="h-36 w-auto rounded-xl border border-pink-200 object-cover shadow-sm">
                    </template>
                    <template x-if="!preview">
                        <div class="h-24 w-32 rounded-xl border-2 border-dashed border-pink-200 bg-pink-50 flex flex-col items-center justify-center text-pink-300 gap-1">
                            <i class="ph ph-image text-2xl"></i>
                            <span class="text-[10px] font-medium">Belum ada foto</span>
                        </div>
                    </template>
                </div>

                <input type="url" id="foto" name="foto"
                       value="{{ old('foto', $menu->foto) }}"
                       @input="updatePreview($event.target.value)"
                       class="form-input @error('foto') error @enderror"
                       placeholder="https://contoh.com/gambar-menu.jpg">
                <p class="text-xs text-gray-400 mt-1.5">Masukkan URL gambar. Kosongkan jika tidak ingin mengubah foto.</p>
                @error('foto')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Stok --}}
            <div>
                <label for="stok" class="form-label">
                    <i class="ph ph-stack mr-1 text-pink-500"></i>
                    Stok <span class="text-red-400">*</span>
                </label>
                <div class="flex items-start gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <input type="number" id="stok" name="stok"
                                   value="{{ old('stok', $menu->stok) }}"
                                   min="0" required
                                   class="form-input w-40 @error('stok') error @enderror"
                                   placeholder="0">
                            <span class="text-sm text-gray-400">porsi</span>
                        </div>
                        @error('stok')
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                <i class="ph ph-warning-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="mt-1 px-3 py-2 rounded-xl bg-pink-50 border border-pink-100">
                        <p class="text-xs text-pink-700 font-medium">Status otomatis</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">
                            Stok &gt; 0 → <span class="text-green-600 font-semibold">Tersedia</span><br>
                            Stok = 0 → <span class="text-red-500 font-semibold">Habis</span>
                        </p>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">
                    <i class="ph ph-info text-pink-400"></i>
                    Stok saat ini: <strong>{{ $menu->stok }} porsi</strong> — berkurang otomatis setiap ada pesanan masuk.
                </p>
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    <i class="ph ph-floppy-disk"></i> Perbarui Menu
                </button>
                <a href="{{ route('admin.menu.index') }}" class="btn-secondary">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
