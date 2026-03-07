<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role  = $request->query('role', 'user');
        $users = User::where('role', $role)
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('superadmin.users.index', compact('users', 'role'));
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users'],
            'password'       => ['required', Password::min(8)],
            'role'           => ['required', 'in:super_admin,admin,user'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'identifier'     => ['nullable', Rule::when($request->role === 'user', ['digits:5'])],
            'kelas'          => ['nullable', Rule::when($request->role === 'user', ['in:RPL,DKV,AK,MP,BR'])],
            'nama_toko'      => ['nullable', 'string', 'max:100'],
            'nama_bank'      => ['nullable', 'string', 'max:50'],
            'nomor_rekening' => ['nullable', 'string', 'max:30'],
        ]);

        User::create([...$data, 'password' => Hash::make($data['password'])]);

        return redirect()->route('superadmin.users.index', ['role' => $data['role']])->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'identifier'     => ['nullable', Rule::when($user->role === 'user', ['digits:5'])],
            'kelas'          => ['nullable', Rule::when($user->role === 'user', ['in:RPL,DKV,AK,MP,BR'])],
            'nama_toko'      => ['nullable', 'string', 'max:100'],
            'nama_bank'      => ['nullable', 'string', 'max:50'],
            'nomor_rekening' => ['nullable', 'string', 'max:30'],
        ]);

        // Checkbox tidak terkirim saat unchecked, harus ditangani manual
        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('superadmin.users.index', ['role' => $user->role])
            ->with('success', 'Data pengguna diperbarui.');
    }

    public function toggleActive(User $user)
    {
        $newState = !$user->is_active;
        $user->update(['is_active' => $newState]);
        $status = $newState ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }
}
