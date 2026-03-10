@extends('layouts.superadmin')

@section('page-title', 'Pengaturan Pembayaran')
@section('page-subtitle', 'Kelola rekening bank dan QR code e-wallet untuk top-up saldo')

@section('content-inner')
<div x-data="{
    showAdd: false,
    editId: null,
    editData: {},
    openEdit(setting) {
        this.editId = setting.id;
        this.editData = { ...setting };
    },
    closeEdit() { this.editId = null; this.editData = {}; }
}" class="space-y-6">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="ph ph-check-circle text-lg text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="ph ph-x-circle text-lg text-red-500"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Header + Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Metode Pembayaran Top-Up</h2>
            <p class="text-sm text-gray-500 mt-0.5">Tambahkan rekening bank atau QR code e-wallet yang akan ditampilkan kepada pengguna saat melakukan top-up.</p>
        </div>
        <button @click="showAdd = !showAdd"
                class="flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            <i class="ph ph-plus"></i>
            <span>Tambah Metode</span>
        </button>
    </div>

    {{-- Add Form --}}
    <div x-show="showAdd" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-2xl border border-pink-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Tambah Metode Pembayaran Baru</h3>
        <form action="{{ route('superadmin.payment-settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Unik (key) <span class="text-red-500">*</span></label>
                    <input type="text" name="key" value="{{ old('key') }}" placeholder="contoh: bank_bni, gopay, dana"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300" required>
                    <p class="text-xs text-gray-400 mt-1">Huruf kecil, angka, underscore. Tidak bisa diubah setelah disimpan.</p>
                    @error('key')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe <span class="text-red-500">*</span></label>
                    <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="bank" {{ old('type') === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="ewallet" {{ old('type') === 'ewallet' ? 'selected' : '' }}>E-Wallet (QR Code)</option>
                    </select>
                    @error('type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Tampilan <span class="text-red-500">*</span></label>
                    <input type="text" name="label" value="{{ old('label') }}" placeholder="contoh: Bank BNI, GoPay"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300" required>
                    @error('label')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Pemilik Rekening / Akun</label>
                    <input type="text" name="account_name" value="{{ old('account_name') }}" placeholder="contoh: Bendahara SMKN 1"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    @error('account_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Rekening / Nomor HP</label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="contoh: 1234567890"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    @error('account_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Upload QR Code (opsional)</label>
                    <input type="file" name="qr_code" accept="image/*"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    @error('qr_code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="is_active" id="add_is_active" checked value="1"
                           class="w-4 h-4 rounded accent-rose-600">
                    <label for="add_is_active" class="text-sm text-gray-600">Aktif (tampil ke pengguna)</label>
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                    Simpan Metode
                </button>
                <button type="button" @click="showAdd = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                    Batal
                </button>
            </div>
        </form>
    </div>

    {{-- Existing Settings List --}}
    @if($settings->isEmpty())
    <div class="bg-white rounded-2xl border border-pink-100 p-12 text-center">
        <i class="ph ph-credit-card text-4xl text-gray-300 mb-3 block"></i>
        <p class="text-gray-500 text-sm">Belum ada metode pembayaran. Klik "Tambah Metode" untuk memulai.</p>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($settings as $setting)
        <div class="bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    @if($setting->type === 'bank')
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="ph ph-bank text-xl text-blue-600"></i>
                    </div>
                    @else
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="ph ph-qr-code text-xl text-purple-600"></i>
                    </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800">{{ $setting->label }}</p>
                        <span class="text-xs {{ $setting->type === 'bank' ? 'text-blue-500' : 'text-purple-500' }} font-medium">
                            {{ $setting->type === 'bank' ? 'Bank Transfer' : 'E-Wallet' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full font-medium {{ $setting->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $setting->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $setting->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="px-5 py-4 space-y-3">
                @if($setting->account_name)
                <div class="flex items-center gap-3">
                    <i class="ph ph-user text-gray-400 text-base w-4"></i>
                    <span class="text-sm text-gray-700">{{ $setting->account_name }}</span>
                </div>
                @endif
                @if($setting->account_number)
                <div class="flex items-center gap-3">
                    <i class="ph ph-hash text-gray-400 text-base w-4"></i>
                    <span class="text-sm font-mono text-gray-700 tracking-wide">{{ $setting->account_number }}</span>
                    <button onclick="navigator.clipboard.writeText('{{ $setting->account_number }}').then(() => alert('Disalin!'))"
                            class="ml-auto text-xs text-gray-400 hover:text-rose-500 transition" title="Salin">
                        <i class="ph ph-copy"></i>
                    </button>
                </div>
                @endif
                @if($setting->qr_code)
                <div class="flex items-center gap-3">
                    <i class="ph ph-image text-gray-400 text-base w-4"></i>
                    <a href="{{ $setting->qr_code_url }}" target="_blank"
                       class="text-sm text-rose-600 hover:underline">Lihat QR Code</a>
                    <img src="{{ $setting->qr_code_url }}" alt="QR" class="w-16 h-16 object-contain border rounded-lg ml-auto">
                </div>
                @endif
                <div class="flex items-center gap-3">
                    <i class="ph ph-sort-ascending text-gray-400 text-base w-4"></i>
                    <span class="text-xs text-gray-400">Urutan: {{ $setting->sort_order }}</span>
                </div>
            </div>

            {{-- Card Actions --}}
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center gap-2">
                <button @click="openEdit({{ $setting->toJson() }})"
                        class="flex-1 text-center text-xs font-semibold text-gray-600 hover:text-rose-600 py-1.5 rounded-lg hover:bg-rose-50 transition">
                    <i class="ph ph-pencil mr-1"></i>Edit
                </button>
                @if($setting->qr_code)
                <form action="{{ route('superadmin.payment-settings.removeQr', $setting) }}" method="POST"
                      onsubmit="return confirm('Hapus QR Code ini?')">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="text-xs font-semibold text-orange-500 hover:text-orange-700 py-1.5 px-3 rounded-lg hover:bg-orange-50 transition">
                        <i class="ph ph-image-broken mr-1"></i>Hapus QR
                    </button>
                </form>
                @endif
                <form action="{{ route('superadmin.payment-settings.destroy', $setting) }}" method="POST"
                      onsubmit="return confirm('Hapus metode pembayaran ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-xs font-semibold text-red-500 hover:text-red-700 py-1.5 px-3 rounded-lg hover:bg-red-50 transition">
                        <i class="ph ph-trash mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Edit Modal --}}
    <div x-show="editId !== null" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div @click.outside="closeEdit()" x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="font-semibold text-gray-800">Edit Metode Pembayaran</h3>
                <button @click="closeEdit()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            <template x-if="editId">
                <form :action="`/superadmin/payment-settings/${editId}`" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Unik (key)</label>
                        <input type="text" :value="editData.key" disabled
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300" required>
                            <option value="bank" :selected="editData.type === 'bank'">Bank Transfer</option>
                            <option value="ewallet" :selected="editData.type === 'ewallet'">E-Wallet (QR Code)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Tampilan <span class="text-red-500">*</span></label>
                        <input type="text" name="label" :value="editData.label"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Pemilik Rekening / Akun</label>
                        <input type="text" name="account_name" :value="editData.account_name"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Rekening / Nomor HP</label>
                        <input type="text" name="account_number" :value="editData.account_number"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Upload QR Code Baru (kosongkan jika tidak diubah)</label>
                        <input type="file" name="qr_code" accept="image/*"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Urutan Tampil</label>
                        <input type="number" name="sort_order" :value="editData.sort_order" min="0"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="edit_is_active" :checked="editData.is_active" value="1"
                               class="w-4 h-4 rounded accent-rose-600">
                        <label for="edit_is_active" class="text-sm text-gray-600">Aktif</label>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="closeEdit()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2.5 rounded-xl transition">
                            Batal
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection
