<?php

declare(strict_types=1);

namespace Tests\Feature\Guru;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherClassesTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;
    private User $guruA;
    private User $guruB;
    private AcademicYear $academicYear;
    private Semester $semester;
    private Role $roleGuru;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleGuru = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);

        $this->schoolA = School::create(['name' => 'School A', 'npsn' => '111', 'email' => 'a@a.com', 'is_active' => true]);
        $this->schoolB = School::create(['name' => 'School B', 'npsn' => '222', 'email' => 'b@b.com', 'is_active' => true]);

        // Academic context setup
        $this->academicYear = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $this->semester = Semester::create([
            'school_id' => $this->schoolA->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil 26/27',
            'is_active' => true,
        ]);

        app(TenantService::class)->clear();
    }

    private function createTeacher(School $school, string $name, string $email): User
    {
        $user = User::create([
            'school_id' => $school->id,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
        ]);

        Teacher::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'nip' => 'NIP' . rand(1000, 9999),
        ]);

        return $user;
    }

    private function assignTeacherToClass(User $teacherUser, string $className, string $subjectName, School $school): TeacherSubject
    {
        $class = Classroom::create([
            'school_id' => $school->id,
            'name' => $className,
            'grade_level' => '10',
            'education_level' => 'SMA',
        ]);

        $subject = Subject::create([
            'school_id' => $school->id,
            'name' => $subjectName,
            'code' => strtoupper(substr($subjectName, 0, 3)),
        ]);

        return TeacherSubject::create([
            'school_id' => $school->id,
            'teacher_id' => Teacher::where('user_id', $teacherUser->id)->first()->id,
            'subject_id' => $subject->id,
            'class_id' => $class->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);
    }

    public function test_teacher_can_see_assigned_classes()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        $assignment = $this->assignTeacherToClass($this->guruA, 'XI IPA 1', 'Matematika', $this->schoolA);

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.index'));
        $response->assertStatus(200);
        $response->assertSee('XI IPA 1');
        $response->assertSee('Matematika');
    }

    public function test_teacher_gets_empty_state_if_no_assignments()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.index'));
        $response->assertStatus(200);
        $response->assertSee('Belum ada kelas yang ditugaskan');
    }

    public function test_teacher_gets_missing_context_if_no_active_semester()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        
        $this->semester->update(['is_active' => false]);

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.index'));
        $response->assertStatus(200);
        $response->assertSee('Konteks Akademik Tidak Aktif');
    }

    public function test_teacher_cannot_see_classes_assigned_to_other_teachers()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        $this->guruB = $this->createTeacher($this->schoolA, 'Guru B', 'gurub@a.com');

        $this->assignTeacherToClass($this->guruB, 'XII IPS 2', 'Sejarah', $this->schoolA);

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.index'));
        $response->assertStatus(200);
        $response->assertDontSee('XII IPS 2');
        $response->assertDontSee('Sejarah');
    }

    public function test_teacher_can_see_multiple_assignments()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        
        $this->assignTeacherToClass($this->guruA, 'XI IPA 1', 'Matematika', $this->schoolA);
        $this->assignTeacherToClass($this->guruA, 'XI IPA 2', 'Fisika', $this->schoolA);

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.index'));
        $response->assertStatus(200);
        $response->assertSee('XI IPA 1');
        $response->assertSee('Matematika');
        $response->assertSee('XI IPA 2');
        $response->assertSee('Fisika');
    }

    public function test_teacher_can_see_class_detail()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        $assignment = $this->assignTeacherToClass($this->guruA, 'XI IPA 1', 'Matematika', $this->schoolA);

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.show', $assignment));
        $response->assertStatus(200);
        $response->assertSee('Detail Kelas - XI IPA 1');
        $response->assertSee('Informasi Penugasan');
        $response->assertSee('Matematika');
    }

    public function test_teacher_cannot_open_other_teacher_class_detail()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        $this->guruB = $this->createTeacher($this->schoolA, 'Guru B', 'gurub@a.com');

        $assignmentB = $this->assignTeacherToClass($this->guruB, 'XII IPS 2', 'Sejarah', $this->schoolA);

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.show', $assignmentB));
        $response->assertStatus(403);
    }

    public function test_teacher_can_see_registered_students_in_class_detail()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        $assignment = $this->assignTeacherToClass($this->guruA, '7A', 'Matematika', $this->schoolA);
        
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        
        $userSiswa1 = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Ahmad Fauzan',
            'email' => 'ahmad@a.com',
            'password' => bcrypt('password'),
            'role_id' => $roleSiswa->id,
        ]);
        $student1 = \App\Models\Student::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $userSiswa1->id,
            'nis' => '20260001',
            'gender' => 'L',
        ]);
        
        $userSiswa2 = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Siti Aisyah',
            'email' => 'siti@a.com',
            'password' => bcrypt('password'),
            'role_id' => $roleSiswa->id,
        ]);
        $student2 = \App\Models\Student::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $userSiswa2->id,
            'nis' => '20260002',
            'gender' => 'P',
        ]);

        $assignment->classroom->students()->attach([
            $student1->id => ['school_id' => $this->schoolA->id, 'academic_year_id' => $this->academicYear->id],
            $student2->id => ['school_id' => $this->schoolA->id, 'academic_year_id' => $this->academicYear->id],
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.classes.show', $assignment));
        $response->assertStatus(200);
        $response->assertSee('Daftar Siswa Terdaftar');
        $response->assertSee('2 ORANG');
        $response->assertSee('2 Siswa terdaftar');
        $response->assertSee('Ahmad Fauzan');
        $response->assertSee('20260001');
        $response->assertSee('Laki-Laki');
        $response->assertSee('Siti Aisyah');
        $response->assertSee('20260002');
        $response->assertSee('Perempuan');
    }

    public function test_teacher_only_sees_students_enrolled_in_current_class()
    {
        $this->guruA = $this->createTeacher($this->schoolA, 'Guru A', 'gurua@a.com');
        $assignment7A = $this->assignTeacherToClass($this->guruA, '7A', 'Matematika', $this->schoolA);
        $assignment7B = $this->assignTeacherToClass($this->guruA, '7B', 'IPA', $this->schoolA);

        $roleSiswa = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $userSiswa7A = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Siswa Kelas Tujuh A',
            'email' => 'siswa7a@a.com',
            'password' => bcrypt('password'),
            'role_id' => $roleSiswa->id,
        ]);
        $student7A = \App\Models\Student::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $userSiswa7A->id,
            'nis' => '7001',
            'gender' => 'L',
        ]);
        $assignment7A->classroom->students()->attach([
            $student7A->id => ['school_id' => $this->schoolA->id, 'academic_year_id' => $this->academicYear->id]
        ]);

        $userSiswa7B = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Siswa Kelas Tujuh B',
            'email' => 'siswa7b@a.com',
            'password' => bcrypt('password'),
            'role_id' => $roleSiswa->id,
        ]);
        $student7B = \App\Models\Student::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $userSiswa7B->id,
            'nis' => '7002',
            'gender' => 'P',
        ]);
        $assignment7B->classroom->students()->attach([
            $student7B->id => ['school_id' => $this->schoolA->id, 'academic_year_id' => $this->academicYear->id]
        ]);

        // Buka 7A
        $response7A = $this->actingAs($this->guruA)->get(route('guru.classes.show', $assignment7A));
        $response7A->assertStatus(200);
        $response7A->assertSee('Siswa Kelas Tujuh A');
        $response7A->assertDontSee('Siswa Kelas Tujuh B');

        // Buka 7B
        $response7B = $this->actingAs($this->guruA)->get(route('guru.classes.show', $assignment7B));
        $response7B->assertStatus(200);
        $response7B->assertSee('Siswa Kelas Tujuh B');
        $response7B->assertDontSee('Siswa Kelas Tujuh A');
    }
}
