@extends('layouts.superadmin')

@section('title', 'Kelola Pengguna')
@section('page-title', $role === 'admin' ? 'Kelola Penjual' : ($role === 'super_admin' ? 'Super Admin' : 'Kelola Pengguna'))

@section('content-inner')
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
        @foreach(['user' => 'Siswa/Guru', 'admin' => 'Penjual', 'super_admin' => 'Super Admin'] as $r => $label)
        <a href="{{ route('superadmin.users.index', ['role' => $r]) }}"
            class="px-4 py-1.5 rounded-full text-sm font-medium border {{ request('role', 'user') === $r ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
    <a href="{{ route('superadmin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Tambah Akun
    </a>
</div>

<form method="GET" class="mb-5">
    <input type="hidden" name="role" value="{{ $role }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-indigo-400">
</form>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                @if($role === 'user')
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kelas</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo</th>
                @elseif($role === 'admin')
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Toko</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo</th>
                @endif
                <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3.5 font-medium text-gray-800">{{ $user->name }}</td>
                <td class="px-6 py-3.5 text-gray-500">{{ $user->email }}</td>
                @if($role === 'user')
                <td class="px-6 py-3.5 text-gray-500">{{ $user->kelas ?? '-' }}</td>
                <td class="px-6 py-3.5 text-right font-medium">Rp {{ number_format($user->saldo, 0, ',', '.') }}</td>
                @elseif($role === 'admin')
                <td class="px-6 py-3.5 text-gray-500">{{ $user->nama_toko ?? '-' }}</td>
                <td class="px-6 py-3.5 text-right font-medium">Rp {{ number_format($user->saldo, 0, ',', '.') }}</td>
                @endif
                <td class="px-6 py-3.5 text-center">
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-3.5 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('superadmin.users.edit', $user) }}" class="text-xs text-indigo-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('superadmin.users.toggle', $user) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs {{ $user->is_active ? 'text-red-500' : 'text-green-600' }} hover:underline">
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">Tidak ada data pengguna.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>
@endsection
