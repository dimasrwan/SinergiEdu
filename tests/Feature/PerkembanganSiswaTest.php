<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerkembanganSiswaTest extends TestCase
{
    use RefreshDatabase;

    private User $kepalaSekolah;
    private School $schoolA;
    private School $schoolB;
    private Role $roleKepalaSekolah;
    private Role $roleStudent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleKepalaSekolah = Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
        $this->roleStudent = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $this->schoolA = School::create([
            'npsn' => '11111111',
            'name' => 'Sekolah A',
            'email' => 'sekolaha@test.com',
            'is_active' => true,
        ]);

        $this->schoolB = School::create([
            'npsn' => '22222222',
            'name' => 'Sekolah B',
            'email' => 'sekolahb@test.com',
            'is_active' => true,
        ]);

        $this->kepalaSekolah = User::create([
            'role_id' => $this->roleKepalaSekolah->id,
            'school_id' => $this->schoolA->id,
            'name' => 'Kepala Sekolah A',
            'email' => 'kepala@sekolaha.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }

    private function createStudent(School $school, string $name, ?string $nis = null, ?string $nisn = null): Student
    {
        $rand = rand(10000, 99999);
        $user = User::create([
            'school_id' => $school->id,
            'role_id' => $this->roleStudent->id,
            'name' => $name,
            'email' => strtolower(str_replace(' ', '', $name)) . $rand . '@test.com',
            'password' => bcrypt('password'),
        ]);

        return Student::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'nis' => $nis ?? (string) $rand,
            'nisn' => $nisn ?? (string) ($rand + 100000),
            'gender' => 'L',
        ]);
    }

    public function test_kepala_sekolah_can_view_all_students_when_no_filters_applied(): void
    {
        $this->createStudent($this->schoolA, 'Siswa Alpha');
        $this->createStudent($this->schoolA, 'Siswa Beta');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan'));

        $response->assertOk();
        $response->assertSee('Siswa Alpha');
        $response->assertSee('Siswa Beta');
    }

    public function test_empty_filters_does_not_show_only_one_student(): void
    {
        $this->createStudent($this->schoolA, 'Siswa 1');
        $this->createStudent($this->schoolA, 'Siswa 2');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['class_id' => '', 'student_id' => '']));

        $response->assertOk();
        $response->assertSee('Siswa 1');
        $response->assertSee('Siswa 2');
    }

    public function test_filter_by_class_id_returns_only_class_students(): void
    {
        $academicYear = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'name' => '2025/2026',
            'year' => '2025/2026',
            'is_active' => true,
        ]);

        $classA = Classroom::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Kelas VII A',
            'grade_level' => '7',
            'academic_year_id' => $academicYear->id,
        ]);

        $classB = Classroom::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Kelas VII B',
            'grade_level' => '7',
            'academic_year_id' => $academicYear->id,
        ]);

        $student1 = $this->createStudent($this->schoolA, 'Siswa VII A');
        $student2 = $this->createStudent($this->schoolA, 'Siswa VII B');

        $student1->classes()->attach($classA->id, ['school_id' => $this->schoolA->id, 'academic_year_id' => $academicYear->id]);
        $student2->classes()->attach($classB->id, ['school_id' => $this->schoolA->id, 'academic_year_id' => $academicYear->id]);

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['class_id' => $classA->id]));

        $response->assertOk();
        $response->assertSee('Siswa VII A');
        $response->assertDontSee('Siswa VII B');
    }

    public function test_filter_by_student_id_shows_specific_student(): void
    {
        $student1 = $this->createStudent($this->schoolA, 'Siswa Pilihan', '1001', '2001');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['student_id' => $student1->id]));

        $response->assertOk();
        $response->assertSee('Siswa Pilihan');
        $response->assertSee('NIS: 1001');
    }

    public function test_student_id_from_other_school_returns_404(): void
    {
        $studentOther = $this->createStudent($this->schoolB, 'Siswa Sekolah Lain');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['student_id' => $studentOther->id]));

        $response->assertStatus(404);
    }

    public function test_class_id_from_other_school_returns_404(): void
    {
        $academicYearB = AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'name' => '2025/2026',
            'year' => '2025/2026',
            'is_active' => true,
        ]);

        $classOther = Classroom::create([
            'school_id' => $this->schoolB->id,
            'name' => 'Kelas Sekolah B',
            'grade_level' => '7',
            'academic_year_id' => $academicYearB->id,
        ]);

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['class_id' => $classOther->id]));

        $response->assertNotFound();
        $response->assertDontSee('Kelas Sekolah B');
    }

    public function test_manipulative_school_id_query_param_does_not_leak_data(): void
    {
        $this->createStudent($this->schoolB, 'Siswa Rahasia Sekolah B');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['school_id' => $this->schoolB->id]));

        $response->assertOk();
        $response->assertDontSee('Siswa Rahasia Sekolah B');
    }

    public function test_kepala_sekolah_a_cannot_see_students_from_school_b(): void
    {
        $this->createStudent($this->schoolA, 'Siswa Sekolah A');
        $this->createStudent($this->schoolB, 'Siswa Sekolah B');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan'));

        $response->assertOk();
        $response->assertSee('Siswa Sekolah A');
        $response->assertDontSee('Siswa Sekolah B');
    }
}
