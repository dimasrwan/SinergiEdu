<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle proses login.
     */
    public function login(Request $request)
    {
        // Logika login akan diimplementasikan nanti
    }

    /**
     * Handle proses logout.
     */
    public function logout(Request $request)
    {
        // Logika logout akan diimplementasikan nanti
    }
}
