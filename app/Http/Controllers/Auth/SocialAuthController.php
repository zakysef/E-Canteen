<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal masuk dengan Google. Silakan coba lagi.']);
        }

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Existing user — update google_id & avatar if not set
            if (!$user->is_active) {
                return redirect()->route('login')->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.']);
            }

            $user->update([
                'google_id' => $user->google_id ?? $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
            ]);
        } else {
            // New user — create account
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'role'      => 'user',
                'is_active' => true,
            ]);
        }

        Auth::login($user, true);

        return match ($user->role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            default       => redirect()->route('user.dashboard')->with('success', 'Selamat datang, ' . $user->name . '!'),
        };
    }
}
