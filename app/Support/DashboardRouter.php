<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Role;
use App\Models\User;

/**
 * Single source of truth untuk memetakan role user ke nama route dashboard.
 * Digunakan oleh redirect fallback '/dashboard', autentikasi, dan registrasi.
 */
class DashboardRouter
{
    /**
     * Map nama role ke nama route dashboard.
     *
     * @var array<string, string>
     */
    protected const DASHBOARDS = [
        'super_admin' => 'super_admin.dashboard',
        'admin' => 'admin.dashboard',
        'waka' => 'waka.dashboard',
        'guru' => 'guru.dashboard',
        'siswa' => 'siswa.dashboard',
        'orangtua' => 'orangtua.dashboard',
        'pengawas' => 'pengawas.dashboard',
        'kepala_sekolah' => 'kepala-sekolah.dashboard',
    ];

    public static function forUser(?User $user): ?string
    {
        return $user?->role ? self::forRole($user->role) : null;
    }

    public static function forRole(?Role $role): ?string
    {
        return $role ? self::forRoleName($role->name) : null;
    }

    public static function forRoleName(?string $roleName): ?string
    {
        return is_string($roleName) ? (self::DASHBOARDS[$roleName] ?? null) : null;
    }
}
