<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\DashboardRouter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google OAuth.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login dengan Google gagal atau dibatalkan. Silakan coba lagi.',
            ]);
        }

        // Validate basic identity
        $rawEmail = $googleUser->getEmail();
        if (empty($rawEmail)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Identitas email dari Google tidak ditemukan.',
            ]);
        }

        // Check if email identity is verified on Google
        $userPayload = $googleUser->user ?? [];
        $isVerified = isset($userPayload['email_verified']) && filter_var($userPayload['email_verified'], FILTER_VALIDATE_BOOLEAN);
        if (! $isVerified) {
            return redirect()->route('login')->withErrors([
                'email' => 'Email Google Anda belum terverifikasi.',
            ]);
        }

        // Normalize email
        $normalizedEmail = Str::lower(trim($rawEmail));

        // Find existing user (DO NOT auto-register!)
        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Email Google Anda belum terdaftar di SinergiEdu. Silakan hubungi Administrator Sekolah.',
            ]);
        }

        // Validate active status
        if (! $user->is_active) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda sedang tidak aktif. Silakan hubungi Administrator Sekolah.',
            ]);
        }

        // Validate role presence and school_id tenant structure
        if (! $user->role) {
            return redirect()->route('login')->withErrors([
                'email' => 'Konfigurasi akun tidak valid. Silakan hubungi Administrator.',
            ]);
        }

        $roleName = strtolower($user->role->name);
        if ($roleName === 'super_admin' || $roleName === 'superadmin') {
            if ($user->school_id !== null) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Konfigurasi akun tidak valid. Silakan hubungi Administrator.',
                ]);
            }
        } else {
            if ($user->school_id === null) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Konfigurasi sekolah akun tidak valid. Silakan hubungi Administrator Sekolah.',
                ]);
            }
        }

        // Authenticate and regenerate session
        Auth::login($user);
        $request->session()->regenerate();

        $redirectRoute = DashboardRouter::forUser($user) ?? 'dashboard';

        return redirect()->intended(route($redirectRoute, absolute: false));
    }
}
