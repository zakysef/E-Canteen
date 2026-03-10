<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('user.settings', ['user' => auth()->user()]);
    }

    public function updateName(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Nama berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'          => ['required', function ($attr, $val, $fail) {
                if (! Hash::check($val, auth()->user()->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'new_password'              => ['required', Password::min(8), 'confirmed'],
        ]);

        auth()->user()->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
