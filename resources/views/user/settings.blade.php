@extends('layouts.user')

@section('page-title', 'Pengaturan Akun')
@section('page-subtitle', 'Ubah nama tampilan dan password akun Anda')

@section('content-inner')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="ph ph-check-circle text-lg text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
        <p class="font-semibold mb-1"><i class="ph ph-warning mr-1"></i>Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Profile Info Card --}}
    <div class="bg-white rounded-2xl border border-pink-100 shadow-sm p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center font-bold text-xl shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-bold text-gray-800 text-base">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if($user->kelas) Jurusan {{ $user->kelas }} @else Siswa / Guru @endif
                    @if($user->identifier) &nbsp;·&nbsp; NIS: {{ $user->identifier }} @endif
                </p>
            </div>
        </div>

        {{-- Change Name --}}
        <div class="border-t border-gray-100 pt-5">
            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="ph ph-pencil-line text-pink-500"></i> Ubah Nama Tampilan
            </h3>
            <form action="{{ route('user.settings.updateName') }}" method="POST" class="flex items-end gap-3">
                @csrf @method('PATCH')
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Baru</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                </div>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition whitespace-nowrap">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- Change Password Card --}}
    <div class="bg-white rounded-2xl border border-pink-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-1 flex items-center gap-2">
            <i class="ph ph-lock-key text-pink-500"></i> Ubah Password
        </h3>
        <p class="text-xs text-gray-400 mb-5">Gunakan password yang kuat dan berbeda dari akun lain.</p>

        <form action="{{ route('user.settings.updatePassword') }}" method="POST" class="space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Password Saat Ini <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="new_password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter.</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="new_password_confirmation" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>
            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                <i class="ph ph-lock-key-open mr-1"></i> Ubah Password
            </button>
        </form>
    </div>

</div>
@endsection
