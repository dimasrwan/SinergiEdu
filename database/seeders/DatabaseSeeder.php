<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Services\TenantService;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Seed Roles (roles are global)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $roleWaka = Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka Kurikulum']);
        $roleGuru = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $roleOrangTua = Role::firstOrCreate(['name' => 'orangtua'], ['display_name' => 'Orang Tua']);
        $rolePengawas = Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        $roleKepalaSekolah = Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah/Madrasah']);

        $schoolsData = [
            [
                'prefix' => 'a',
                'name' => 'School A',
                'npsn' => '11111111',
                'email' => 'info@schoola.test',
            ],
            [
                'prefix' => 'b',
                'name' => 'School B',
                'npsn' => '22222222',
                'email' => 'info@schoolb.test',
            ]
        ];

        foreach ($schoolsData as $data) {
            $prefix = $data['prefix'];
            
            // Create School
            $school = School::firstOrCreate(
                ['npsn' => $data['npsn']],
                [
                    'name' => $data['name'],
                    'address' => 'Alamat ' . $data['name'],
                    'phone' => '00000000',
                    'email' => $data['email'],
                    'is_active' => true,
                ]
            );

            // Bypass Global Scope for seeding explicitly if needed, but since we are seeding
            // we should set the tenant context to ensure traits don't conflict or we manually pass school_id.
            // Actually, the TenantScoped trait injects school_id IF context is set. If not set, it relies on manual assignment.
            // We manually assign it anyway, but let's set context to be 100% safe against the Global Scope.
            app(TenantService::class)->setSchool($school);

            // Admin
            User::create([
                'school_id' => $school->id,
                'name' => 'Admin ' . strtoupper($prefix),
                'email' => "admin_{$prefix}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roleAdmin->id,
            ]);

            // Waka
            User::create([
                'school_id' => $school->id,
                'name' => 'Waka ' . strtoupper($prefix),
                'email' => "waka_{$prefix}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roleWaka->id,
            ]);

            // Guru
            $userGuru = User::create([
                'school_id' => $school->id,
                'name' => 'Guru ' . strtoupper($prefix),
                'email' => "guru_{$prefix}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roleGuru->id,
            ]);

            Teacher::create([
                'school_id' => $school->id,
                'user_id' => $userGuru->id,
                'nip' => '19820315200912100' . rand(1,9),
                'phone' => '081234567890',
                'address' => 'Jl. Guru ' . strtoupper($prefix),
            ]);

            // Siswa
            $userSiswa = User::create([
                'school_id' => $school->id,
                'name' => 'Siswa ' . strtoupper($prefix),
                'email' => "siswa_{$prefix}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roleSiswa->id,
            ]);

            $student = Student::create([
                'school_id' => $school->id,
                'user_id' => $userSiswa->id,
                'parent_id' => null,
                'nisn' => '006789123' . rand(1,9),
                'nis' => '22231010' . rand(1,9),
                'gender' => 'L',
            ]);

            // Orang Tua
            $userOrangTua = User::create([
                'school_id' => $school->id,
                'name' => 'Ortu ' . strtoupper($prefix),
                'email' => "ortu_{$prefix}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roleOrangTua->id,
            ]);

            $parent = StudentParent::create([
                'school_id' => $school->id,
                'user_id' => $userOrangTua->id,
                'phone' => '089876543210',
                'address' => 'Jl. Ortu ' . strtoupper($prefix),
            ]);

            $student->update(['parent_id' => $parent->id]);

            // Create some classes & subjects
            \App\Models\Classroom::create([
                'school_id' => $school->id,
                'education_level' => 'SMA',
                'name' => 'Kelas X-' . strtoupper($prefix),
                'grade_level' => '10',
                'academic_year_id' => null,
                'homeroom_teacher_id' => $userGuru->teacher->id ?? null,
            ]);

            \App\Models\Subject::create([
                'school_id' => $school->id,
                'name' => 'Matematika ' . strtoupper($prefix),
                'code' => 'MAT-' . strtoupper($prefix),
            ]);
        }

        // Clear tenant context at the end of seeder
        app(TenantService::class)->clear();
    }
}
