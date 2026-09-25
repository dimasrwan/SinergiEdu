<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Support\DashboardRouter;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Role yang boleh dipilih pada registrasi mandiri.
     * Role platform (super_admin, admin) dibuat via Seeder/Admin, bukan lewat registrasi publik.
     */
    protected const SELF_REGISTERABLE_ROLES = [
        'siswa',
        'orangtua',
        'guru',
        'waka',
        'kepala_sekolah',
        'pengawas',
    ];

    /**
     * Role default saat form registrasi tidak mengirim field `role`.
     */
    protected const DEFAULT_ROLE = 'siswa';

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        abort(404);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        abort(404);
    }
}
