@extends('layouts.admin')

@section('title', 'Tambah Menu Baru')
@section('page-title', 'Tambah Menu Baru')

@section('content-inner')

<div class="max-w-2xl">

    {{-- Back link --}}
    <a href="{{ route('admin.menu.index') }}"
       class="inline-flex items-center gap-2 text-sm text-pink-600 hover:text-pink-700 font-medium mb-6">
        <i class="ph ph-arrow-left"></i> Kembali ke Daftar Menu
    </a>

    <div class="card p-6 lg:p-8"
         x-data="{
             preview: null,
             updatePreview(val) {
                 this.preview = val.trim() || null;
             }
         }">

        <h2 class="text-base font-semibold text-gray-800 mb-6 pb-4 border-b border-pink-100">
            Informasi Menu Baru
        </h2>

        <form method="POST" action="{{ route('admin.menu.store') }}" class="space-y-5">
            @csrf

            {{-- Nama Menu --}}
            <div>
                <label for="nama" class="form-label">
                    Nama Menu <span class="text-red-400">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                       class="form-input @error('nama') error @enderror"
                       placeholder="Contoh: Nasi Goreng Spesial">
                @error('nama')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kategori & Harga (two columns) --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="kategori" class="form-label">
                        Kategori <span class="text-red-400">*</span>
                    </label>
                    <select id="kategori" name="kategori" required class="form-input">
                        <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih kategori</option>
                        <option value="makanan" {{ old('kategori') === 'makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="minuman" {{ old('kategori') === 'minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="snack"   {{ old('kategori') === 'snack'   ? 'selected' : '' }}>Snack</option>
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
                        <input type="number" id="harga" name="harga" value="{{ old('harga') }}"
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
                          placeholder="Deskripsi singkat menu (opsional)...">{{ old('deskripsi') }}</textarea>
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

                {{-- Preview area --}}
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
                       value="{{ old('foto') }}"
                       @input="updatePreview($event.target.value)"
                       class="form-input @error('foto') error @enderror"
                       placeholder="https://contoh.com/gambar-menu.jpg">
                <p class="text-xs text-gray-400 mt-1.5">Masukkan URL gambar menu (opsional).</p>
                @error('foto')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Stok Awal --}}
            <div>
                <label for="stok" class="form-label">
                    <i class="ph ph-stack mr-1 text-pink-500"></i>
                    Stok Awal <span class="text-red-400">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" id="stok" name="stok"
                           value="{{ old('stok', 0) }}"
                           min="0" required
                           class="form-input w-40 @error('stok') error @enderror"
                           placeholder="0">
                    <span class="text-sm text-gray-400">porsi</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">
                    <i class="ph ph-info text-pink-400"></i>
                    Status menu (tersedia/habis) otomatis berdasarkan stok. Stok berkurang setiap ada pesanan masuk.
                </p>
                @error('stok')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    <i class="ph ph-floppy-disk"></i> Simpan Menu
                </button>
                <a href="{{ route('admin.menu.index') }}" class="btn-secondary">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
