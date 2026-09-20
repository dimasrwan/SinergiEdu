<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Models\KepalaSekolah;
use App\Models\Pengawas;
use App\Services\TenantService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KepalaSekolahKomiteSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        $tenantService = app(TenantService::class);
        $roles = Role::pluck('id', 'name');

        $school = School::query()->first();
        if (!$school) {
            return;
        }

        $tenantService->setSchool($school);

        // Kepala Sekolah
        $kepsek = User::updateOrCreate(
            ['email' => 'kepsek@dummy.test'],
            [
                'name' => 'Kepala Sekolah Dummy',
                'password' => $password,
                'role_id' => $roles['kepala_sekolah'] ?? null,
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );
        KepalaSekolah::updateOrCreate(
            ['user_id' => $kepsek->id],
            [
                'school_id' => $school->id,
                'nip' => '1234567890',
                'phone' => '08111111111',
                'address' => 'Alamat Kepsek',
            ]
        );

        // Komite
        $komite = User::updateOrCreate(
            ['email' => 'komite@dummy.test'],
            [
                'name' => 'Komite Sekolah Dummy',
                'password' => $password,
                'role_id' => $roles['pengawas'] ?? null,
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );
        Pengawas::updateOrCreate(
            ['user_id' => $komite->id],
            [
                'school_id' => $school->id,
                'nip' => '0987654321',
                'phone' => '08222222222',
                'address' => 'Alamat Komite',
            ]
        );

        $tenantService->clear();
    }
}
