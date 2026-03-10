<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /** Show account settings page (own account + user management) */
    public function index()
    {
        $superAdmins = User::where('role', 'super_admin')->orderBy('name')->get();
        $users       = User::where('role', 'user')->orderBy('name')->get();
        $sellers     = User::where('role', 'admin')->orderBy('name')->get();

        return view('superadmin.settings.index', compact('superAdmins', 'users', 'sellers'));
    }

    /** Update username (name) for any account */
    public function updateName(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->update($data);

        return back()->with('success', "Nama akun \"{$user->name}\" berhasil diperbarui.");
    }

    /** Update password for any account */
    public function updatePassword(Request $request, User $user)
    {
        $rules = ['new_password' => ['required', Password::min(8), 'confirmed']];

        // Extra check if changing own password
        if ($user->id === auth()->id()) {
            $rules['current_password'] = ['required', function ($attr, $val, $fail) {
                if (! Hash::check($val, auth()->user()->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }];
        }

        $request->validate($rules);

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', "Password akun \"{$user->name}\" berhasil diubah.");
    }

    /** Reset password to a default value  */
    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate([
            'default_password' => ['required', Password::min(8)],
        ]);

        $user->update(['password' => Hash::make($data['default_password'])]);

        return back()->with('success', "Password akun \"{$user->name}\" berhasil direset.");
    }

    /** Bulk-reset password for a role */
    public function bulkResetPassword(Request $request)
    {
        $data = $request->validate([
            'role'             => ['required', 'in:user,admin,super_admin'],
            'default_password' => ['required', Password::min(8)],
        ]);

        $count = User::where('role', $data['role'])
            ->where('id', '!=', auth()->id()) // don't reset own password
            ->get()
            ->each(fn($u) => $u->update(['password' => Hash::make($data['default_password'])]));

        $label = match ($data['role']) {
            'user'        => 'Siswa/Guru',
            'admin'       => 'Penjual',
            'super_admin' => 'Super Admin',
        };

        return back()->with('success', "Password semua akun {$label} (" . $count->count() . " akun) berhasil direset.");
    }
}
