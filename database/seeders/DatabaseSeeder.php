<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Seed Roles
        $roleAdmin = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $roleWaka = Role::create(['name' => 'waka', 'display_name' => 'Waka Kurikulum']);
        $roleGuru = Role::create(['name' => 'guru', 'display_name' => 'Guru']);
        $roleSiswa = Role::create(['name' => 'siswa', 'display_name' => 'Siswa']);
        $roleOrangTua = Role::create(['name' => 'orangtua', 'display_name' => 'Orang Tua']);
        $rolePengawas = Role::create(['name' => 'pengawas', 'display_name' => 'Pengawas']);
        $roleKepalaSekolah = Role::create(['name' => 'kepala_sekolah', 'display_name' => 'Kepala Sekolah/Madrasah']);

        // 2. Buat Akun Admin
        User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@sinergiedu.test',
            'password' => $password,
            'role_id' => $roleAdmin->id,
        ]);

        // 3. Buat Akun Waka Kurikulum
        User::create([
            'name' => 'Demo Waka Kurikulum',
            'email' => 'waka@sinergiedu.test',
            'password' => $password,
            'role_id' => $roleWaka->id,
        ]);

        // 4. Buat Akun Pengawas
        User::create([
            'name' => 'Demo Pengawas',
            'email' => 'pengawas@sinergiedu.test',
            'password' => $password,
            'role_id' => $rolePengawas->id,
        ]);

        // 5. Buat Akun Kepala Sekolah
        User::create([
            'name' => 'Demo Kepala Sekolah',
            'email' => 'kepala@sinergiedu.test',
            'password' => $password,
            'role_id' => $roleKepalaSekolah->id,
        ]);

        // 5. Buat Akun Guru & Profil Teacher
        $userGuru = User::create([
            'name' => 'Demo Guru',
            'email' => 'guru@sinergiedu.test',
            'password' => $password,
            'role_id' => $roleGuru->id,
        ]);

        Teacher::create([
            'user_id' => $userGuru->id,
            'nip' => '198203152009121003',
            'phone' => '081234567890',
            'address' => 'Jl. Pendidikan No. 12',
        ]);

        // 6. Buat Akun Siswa & Profil Student
        $userSiswa = User::create([
            'name' => 'Demo Siswa',
            'email' => 'siswa@sinergiedu.test',
            'password' => $password,
            'role_id' => $roleSiswa->id,
        ]);

        $student = Student::create([
            'user_id' => $userSiswa->id,
            'parent_id' => null, // di-link ke parent nanti setelah parent dibuat
            'nisn' => '0067891234',
            'nis' => '222310101',
            'gender' => 'L',
        ]);

        // 7. Buat Akun Orang Tua & Profil StudentParent
        $userOrangTua = User::create([
            'name' => 'Demo Orang Tua',
            'email' => 'orangtua@sinergiedu.test',
            'password' => $password,
            'role_id' => $roleOrangTua->id,
        ]);

        $parent = StudentParent::create([
            'user_id' => $userOrangTua->id,
            'phone' => '089876543210',
            'address' => 'Jl. Keluarga No. 45',
        ]);

        // Link student ke parent
        $student->update(['parent_id' => $parent->id]);
    }
}
