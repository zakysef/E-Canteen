@extends('layouts.superadmin')

@section('page-title', 'Pengaturan Akun')
@section('page-subtitle', 'Kelola username dan password akun Super Admin, Pengguna, dan Penjual')

@section('content-inner')
<div x-data="{ activeTab: 'own', search: '' }" class="space-y-6">

    {{-- Flash --}}
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
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
        <p class="font-semibold mb-1"><i class="ph ph-warning mr-1"></i>Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 bg-white rounded-xl border border-pink-100 p-1 shadow-sm w-fit">
        @php
        $tabs = [
            ['key' => 'own',        'label' => 'Akun Saya',       'icon' => 'ph-user-gear'],
            ['key' => 'superadmin', 'label' => 'Super Admin',     'icon' => 'ph-shield-star'],
            ['key' => 'users',      'label' => 'Siswa / Guru',    'icon' => 'ph-student'],
            ['key' => 'sellers',    'label' => 'Penjual',         'icon' => 'ph-storefront'],
            ['key' => 'bulk',       'label' => 'Reset Massal',    'icon' => 'ph-arrows-clockwise'],
        ];
        @endphp
        @foreach($tabs as $tab)
        <button @click="activeTab = '{{ $tab['key'] }}'"
                :class="activeTab === '{{ $tab['key'] }}' ? 'bg-rose-600 text-white shadow' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50'"
                class="flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-lg transition-all whitespace-nowrap">
            <i class="ph {{ $tab['icon'] }} text-sm"></i>
            {{ $tab['label'] }}
        </button>
        @endforeach
    </div>

    {{-- ═══ TAB: Akun Saya ══════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'own'" x-transition class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @php $me = auth()->user(); @endphp

        {{-- Change Name --}}
        <div class="bg-white rounded-2xl border border-pink-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-1 flex items-center gap-2">
                <i class="ph ph-pencil-line text-rose-500"></i> Ubah Nama
            </h3>
            <p class="text-xs text-gray-400 mb-4">Nama yang ditampilkan di sistem</p>
            <form action="{{ route('superadmin.settings.updateName', $me) }}" method="POST" class="space-y-3">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Baru</label>
                    <input type="text" name="name" value="{{ old('name', $me->name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                    Simpan Nama
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-2xl border border-pink-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-1 flex items-center gap-2">
                <i class="ph ph-lock-key text-rose-500"></i> Ubah Password
            </h3>
            <p class="text-xs text-gray-400 mb-4">Masukkan password saat ini untuk konfirmasi</p>
            <form action="{{ route('superadmin.settings.updatePassword', $me) }}" method="POST" class="space-y-3">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru</label>
                    <input type="password" name="new_password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                    Ubah Password
                </button>
            </form>
        </div>
    </div>

    {{-- ═══ TAB: Super Admin / Users / Sellers — shared template =════════════ --}}
    @foreach([
        ['key' => 'superadmin', 'collection' => $superAdmins, 'roleLabel' => 'Super Admin', 'icon' => 'ph-shield-star', 'color' => 'rose'],
        ['key' => 'users',      'collection' => $users,       'roleLabel' => 'Siswa / Guru', 'icon' => 'ph-student',     'color' => 'sky'],
        ['key' => 'sellers',    'collection' => $sellers,     'roleLabel' => 'Penjual',      'icon' => 'ph-storefront',  'color' => 'violet'],
    ] as $tab)
    <div x-show="activeTab === '{{ $tab['key'] }}'" x-transition class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-{{ $tab['color'] }}-100 flex items-center justify-center">
                <i class="ph {{ $tab['icon'] }} text-{{ $tab['color'] }}-600 text-lg"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Kelola Akun {{ $tab['roleLabel'] }}</h3>
                <p class="text-xs text-gray-400">{{ $tab['collection']->count() }} akun terdaftar</p>
            </div>
        </div>

        @if($tab['collection']->isEmpty())
        <div class="bg-white rounded-2xl border border-pink-100 p-10 text-center text-gray-400 text-sm">
            <i class="ph {{ $tab['icon'] }} text-3xl block mb-2"></i>
            Belum ada akun {{ $tab['roleLabel'] }}.
        </div>
        @else
        <div class="space-y-3">
            @foreach($tab['collection'] as $u)
            <div x-data="{ openEdit: null }" class="bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden">
                {{-- User row --}}
                <div class="flex items-center gap-4 px-5 py-4">
                    <div class="w-10 h-10 rounded-full bg-{{ $tab['color'] }}-100 text-{{ $tab['color'] }}-700 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr($u->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800">{{ $u->name }}</p>
                        <p class="text-xs text-gray-400">{{ $u->email }}
                            @if($u->id === auth()->id())
                            <span class="ml-1 text-xs bg-rose-100 text-rose-600 px-1.5 py-0.5 rounded-full font-medium">Anda</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="openEdit = openEdit === 'name' ? null : 'name'"
                                :class="openEdit === 'name' ? 'bg-gray-200' : 'bg-gray-100 hover:bg-gray-200'"
                                class="text-xs font-semibold text-gray-600 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            <i class="ph ph-pencil text-xs"></i> Nama
                        </button>
                        <button @click="openEdit = openEdit === 'password' ? null : 'password'"
                                :class="openEdit === 'password' ? 'bg-gray-200' : 'bg-gray-100 hover:bg-gray-200'"
                                class="text-xs font-semibold text-gray-600 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            <i class="ph ph-lock-key text-xs"></i> Password
                        </button>
                    </div>
                </div>

                {{-- Edit name panel --}}
                <div x-show="openEdit === 'name'" x-transition class="border-t border-gray-50 px-5 py-4 bg-gray-50/50">
                    <form action="{{ route('superadmin.settings.updateName', $u) }}" method="POST"
                          class="flex items-center gap-3">
                        @csrf @method('PATCH')
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Baru untuk "{{ $u->name }}"</label>
                            <input type="text" name="name" value="{{ $u->name }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                        </div>
                        <button type="submit" class="mt-5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition whitespace-nowrap">
                            Simpan
                        </button>
                    </form>
                </div>

                {{-- Reset password panel --}}
                <div x-show="openEdit === 'password'" x-transition class="border-t border-gray-50 px-5 py-4 bg-gray-50/50">
                    <form action="{{ route('superadmin.settings.resetPassword', $u) }}" method="POST"
                          onsubmit="return confirm('Reset password akun {{ addslashes($u->name) }}?')"
                          class="flex items-center gap-3">
                        @csrf @method('PATCH')
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru untuk "{{ $u->name }}"</label>
                            <input type="password" name="default_password" required placeholder="Min. 8 karakter"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                        </div>
                        <button type="submit" class="mt-5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition whitespace-nowrap">
                            <i class="ph ph-lock-key-open mr-1"></i>Reset
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach

    {{-- ═══ TAB: Reset Massal ═══════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'bulk'" x-transition class="max-w-lg space-y-6">
        <div class="bg-white rounded-2xl border border-pink-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-1 flex items-center gap-2">
                <i class="ph ph-arrows-clockwise text-rose-500"></i> Reset Password Default Massal
            </h3>
            <p class="text-xs text-gray-400 mb-5">Reset semua password satu role ke password yang sama sekaligus. Cocok untuk awal tahun ajaran baru.</p>

            <form action="{{ route('superadmin.settings.bulkResetPassword') }}" method="POST"
                  onsubmit="return confirm('Reset password semua akun di role yang dipilih? Tindakan ini tidak bisa dibatalkan.')"
                  class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Target Role <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                        <option value="">-- Pilih Role --</option>
                        <option value="user">Siswa / Guru ({{ $users->count() }} akun)</option>
                        <option value="admin">Penjual ({{ $sellers->count() }} akun)</option>
                        <option value="super_admin">Super Admin ({{ $superAdmins->count() }} akun, kecuali akun Anda)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password Default Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="default_password" required placeholder="Min. 8 karakter"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </div>
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700">
                    <i class="ph ph-warning-diamond mr-1"></i>
                    <strong>Peringatan:</strong> Semua pengguna di role yang dipilih akan diarahkan untuk login ulang dengan password baru.
                    Informasikan password ini kepada mereka.
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                    <i class="ph ph-arrows-clockwise mr-1"></i> Reset Password Massal
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
