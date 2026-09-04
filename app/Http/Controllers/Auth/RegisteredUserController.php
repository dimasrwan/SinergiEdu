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
        $roles = Role::whereIn('name', self::SELF_REGISTERABLE_ROLES)
            ->orderBy('display_name')
            ->get();

        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', Rule::in(self::SELF_REGISTERABLE_ROLES)],
        ]);

        // Role ditentukan EKSPLISIT dari request; tidak ada fallback diam-diam ke role lain.
        // Jika field `role` tidak dikirim, gunakan DEFAULT_ROLE; jika dikirim namun tidak valid,
        // validasi di atas akan gagal (bukan jatuh ke siswa).
        $roleName = $request->input('role', self::DEFAULT_ROLE);
        $role = Role::firstOrCreate(
            ['name' => $roleName],
            ['display_name' => ucwords(str_replace('_', ' ', $roleName))]
        );

        $school = School::firstOrCreate(
            ['npsn' => 'TEST'],
            ['name' => 'Test School', 'email' => 'test@school.com', 'is_active' => true]
        );

        $user = User::create([
            'school_id' => $school->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect sesuai role yang benar-benar tersimpan, bukan hardcode ke /siswa/dashboard.
        $redirectRoute = DashboardRouter::forUser($user) ?? 'siswa.dashboard';

        return redirect()->route($redirectRoute);
    }
}
