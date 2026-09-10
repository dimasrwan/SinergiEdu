<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\KepalaSekolah;
use App\Models\Role;
use App\Models\School;
use App\Models\Scopes\TenantScope;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder khusus untuk membuat 1 akun Kepala Sekolah default.
 *
 * Idempotent: jika email sudah ada, role & profil dipastikan benar
 * (tidak akan ter-fallback ke role lain seperti siswa).
 *
 * Login default:
 *   email: kepalasekolah@sinergiedu.test
 *   password: password
 */
class KepalaSekolahSeeder extends Seeder
{
    protected const DEFAULT_EMAIL = 'kepalasekolah@sinergiedu.test';

    protected const DEFAULT_PASSWORD = 'password';

    protected const DEFAULT_NIP = '198001012000031002';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'kepala_sekolah'],
            ['display_name' => 'Kepala Sekolah/Madrasah']
        );

        $school = School::query()->orderBy('id')->first();
        if (! $school) {
            $school = School::create([
                'name' => 'School A',
                'npsn' => '11111111',
                'address' => 'Alamat School A',
                'phone' => '00000000',
                'email' => 'info@schoola.test',
                'is_active' => true,
            ]);
        }

        // Konteks tenant diset agar school_id terisi otomatis saat user baru dibuat.
        $schoolHasUsers = $school->users()->count() > 0;
        app(TenantService::class)->setSchool($school);

        // Buat user dengan role EKSPLISIT kepala_sekolah (bukan fallback ke siswa).
        $user = User::firstOrCreate(
            ['email' => self::DEFAULT_EMAIL],
            [
                'name' => 'Kepala Sekolah',
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'role_id' => $role->id,
                'email_verified_at' => now(),
            ]
        );

        // Pastikan konteks mengikuti sekolah milik user (relevan untuk akun lama).
        if (! $schoolHasUsers && $user->school_id && $user->school) {
            app(TenantService::class)->setSchool($user->school);
        }

        // firstOrCreate tidak memperbarui record lama; jamin role & verifikasi benar.
        User::withoutGlobalScope(TenantScope::class)
            ->where('id', $user->id)
            ->update([
                'name' => 'Kepala Sekolah',
                'role_id' => $role->id,
                'email_verified_at' => now(),
            ]);

        KepalaSekolah::withoutGlobalScope(TenantScope::class)
            ->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'school_id' => $user->school_id,
                    'nip' => self::DEFAULT_NIP,
                    'phone' => '081234567890',
                    'address' => 'Kantor Kepala Sekolah',
                ]
            );

        app(TenantService::class)->clear();
    }
}
