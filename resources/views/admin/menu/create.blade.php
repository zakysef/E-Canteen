@extends('layouts.admin')

@section('title', 'Tambah Menu Baru')
@section('page-title', 'Tambah Menu Baru')

@section('content-inner')

<div class="max-w-2xl">

    {{-- Back link --}}
    <a href="{{ route('admin.menu.index') }}"
       class="inline-flex items-center gap-2 text-sm text-orange-600 hover:text-orange-700 font-medium mb-6">
        <i class="ph ph-arrow-left"></i> Kembali ke Daftar Menu
    </a>

    <div class="card p-6 lg:p-8"
         x-data="{
             preview: null,
             handleFile(e) {
                 const file = e.target.files[0];
                 if (!file) return;
                 const reader = new FileReader();
                 reader.onload = (ev) => { this.preview = ev.target.result; };
                 reader.readAsDataURL(file);
             }
         }">

        <h2 class="text-base font-semibold text-gray-800 mb-6 pb-4 border-b border-pink-100">
            Informasi Menu Baru
        </h2>

        <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" class="space-y-5">
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
                    <i class="ph ph-image mr-1"></i> Foto Menu
                </label>

                {{-- Preview area --}}
                <div class="mb-3">
                    <template x-if="preview">
                        <div class="relative inline-block">
                            <img :src="preview" alt="Preview foto"
                                 class="h-36 w-auto rounded-xl border border-pink-200 object-cover shadow-sm">
                            <button type="button" @click="preview = null; $refs.fotoInput.value = ''"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 shadow">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                    </template>
                    <template x-if="!preview">
                        <div class="h-24 w-32 rounded-xl border-2 border-dashed border-pink-200 bg-pink-50 flex flex-col items-center justify-center text-pink-300 gap-1">
                            <i class="ph ph-upload-simple text-2xl"></i>
                            <span class="text-[10px] font-medium">Belum ada foto</span>
                        </div>
                    </template>
                </div>

                <input type="file" id="foto" name="foto" accept="image/*"
                       x-ref="fotoInput"
                       @change="handleFile($event)"
                       class="block w-full text-sm text-gray-600
                              file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0
                              file:text-sm file:font-semibold file:bg-orange-600 file:text-white
                              hover:file:bg-orange-700 file:transition-colors file:cursor-pointer">
                @error('foto')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="form-label">Status Awal</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="radio" name="status" value="tersedia"
                               {{ old('status', 'tersedia') === 'tersedia' ? 'checked' : '' }}
                               class="w-4 h-4 accent-green-600">
                        <span class="text-sm text-gray-700 group-hover:text-gray-900 font-medium">
                            <i class="ph ph-toggle-right text-green-600 mr-1"></i>Tersedia
                        </span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="radio" name="status" value="habis"
                               {{ old('status') === 'habis' ? 'checked' : '' }}
                               class="w-4 h-4 accent-red-500">
                        <span class="text-sm text-gray-700 group-hover:text-gray-900 font-medium">
                            <i class="ph ph-toggle-left text-red-500 mr-1"></i>Habis
                        </span>
                    </label>
                </div>
                @error('status')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="ph ph-warning-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
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
