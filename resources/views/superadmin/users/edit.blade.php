@extends('layouts.superadmin')

@section('title', 'Edit Akun')
@section('page-title', 'Edit Akun — ' . $user->name)

@section('content-inner')
<div class="max-w-2xl">
    <a href="{{ route('superadmin.users.index', ['role' => $user->role]) }}" class="text-sm text-indigo-600 hover:underline mb-6 block">← Kembali</a>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('superadmin.users.update', $user) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>
            @if($user->role === 'user')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NIS / NIP</label>
                    <input type="text" name="identifier" value="{{ old('identifier', $user->identifier) }}"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas / Jabatan</label>
                    <input type="text" name="kelas" value="{{ old('kelas', $user->kelas) }}"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>
            @endif
            @if($user->role === 'admin')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko', $user->nama_toko) }}"
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Bank</label>
                    <input type="text" name="nama_bank" value="{{ old('nama_bank', $user->nama_bank) }}"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $user->nomor_rekening) }}"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>
            @endif
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded text-indigo-600">
                    <span class="text-gray-700">Akun aktif</span>
                </label>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-8 py-2.5 rounded-lg font-semibold hover:bg-indigo-700">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
