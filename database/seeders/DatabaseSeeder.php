<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles (roles are global)
        Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka Kurikulum']);
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        Role::firstOrCreate(['name' => 'orangtua'], ['display_name' => 'Orang Tua']);
        Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah/Madrasah']);

        if (app()->environment('local', 'testing')) {
            $this->call(DevelopmentTestDataSeeder::class);
        }
    }
}
