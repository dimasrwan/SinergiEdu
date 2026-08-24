<?php

declare(strict_types=1);

namespace Tests\Feature\Guru;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherDashboardTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private User $guru;
    private AcademicYear $academicYear;
    private Semester $semester;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $this->school = School::create(['name' => 'School A', 'npsn' => '111', 'email' => 'a@a.com', 'is_active' => true]);

        $this->academicYear = AcademicYear::create([
            'school_id' => $this->school->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $this->semester = Semester::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil',
            'is_active' => true,
        ]);

        $this->guru = User::create([
            'school_id' => $this->school->id,
            'name' => 'Guru A',
            'email' => 'guru@a.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'guru')->first()->id,
        ]);

        Teacher::create([
            'school_id' => $this->school->id,
            'user_id' => $this->guru->id,
            'nip' => '123',
        ]);

        app(TenantService::class)->clear();
    }

    public function test_dashboard_can_be_accessed_and_shows_empty_state_if_no_classes()
    {
        $response = $this->actingAs($this->guru)->get(route('guru.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Anda belum memiliki kelas aktif');
        $response->assertSee('0'); // for stat cards
    }

    public function test_dashboard_shows_missing_context_message()
    {
        $this->semester->update(['is_active' => false]);
        $response = $this->actingAs($this->guru)->get(route('guru.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Konteks Akademik Tidak Aktif');
    }

    public function test_dashboard_calculates_unique_classes_and_students()
    {
        // 1 Class, 2 Subjects, 2 Students
        $class = Classroom::create(['school_id' => $this->school->id, 'name' => 'X IPA 1', 'grade_level' => '10', 'education_level' => 'SMA']);
        $subject1 = Subject::create(['school_id' => $this->school->id, 'name' => 'Matematika', 'code' => 'MAT']);
        $subject2 = Subject::create(['school_id' => $this->school->id, 'name' => 'Fisika', 'code' => 'FIS']);
        
        TeacherSubject::create([
            'school_id' => $this->school->id,
            'teacher_id' => Teacher::where('user_id', $this->guru->id)->first()->id,
            'subject_id' => $subject1->id,
            'class_id' => $class->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        TeacherSubject::create([
            'school_id' => $this->school->id,
            'teacher_id' => Teacher::where('user_id', $this->guru->id)->first()->id,
            'subject_id' => $subject2->id,
            'class_id' => $class->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        $student1User = User::create(['school_id' => $this->school->id, 'name' => 'Siswa 1', 'email' => 's1@a.com', 'password' => 'x', 'role_id' => Role::where('name', 'siswa')->first()->id]);
        $student2User = User::create(['school_id' => $this->school->id, 'name' => 'Siswa 2', 'email' => 's2@a.com', 'password' => 'x', 'role_id' => Role::where('name', 'siswa')->first()->id]);
        
        $student1 = Student::create(['school_id' => $this->school->id, 'user_id' => $student1User->id, 'nisn' => '1']);
        $student2 = Student::create(['school_id' => $this->school->id, 'user_id' => $student2User->id, 'nisn' => '2']);

        StudentClass::create(['school_id' => $this->school->id, 'student_id' => $student1->id, 'class_id' => $class->id, 'academic_year_id' => $this->academicYear->id]);
        StudentClass::create(['school_id' => $this->school->id, 'student_id' => $student2->id, 'class_id' => $class->id, 'academic_year_id' => $this->academicYear->id]);

        $response = $this->actingAs($this->guru)->get(route('guru.dashboard'));
        $response->assertStatus(200);

        // Assert 1 unique class
        $response->assertSee('<h3 class="text-2xl font-bold text-slate-900">1</h3>', false);
        // Assert 2 unique students
        $response->assertSee('<h3 class="text-2xl font-bold text-slate-900">2</h3>', false);
        $response->assertSee('X IPA 1');
    }

    public function test_dashboard_shows_unscored_tasks()
    {
        $class = Classroom::create(['school_id' => $this->school->id, 'name' => 'X IPA 1', 'grade_level' => '10', 'education_level' => 'SMA']);
        $subject = Subject::create(['school_id' => $this->school->id, 'name' => 'Matematika', 'code' => 'MAT']);
        
        TeacherSubject::create([
            'school_id' => $this->school->id,
            'teacher_id' => Teacher::where('user_id', $this->guru->id)->first()->id,
            'subject_id' => $subject->id,
            'class_id' => $class->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        $studentUser = User::create(['school_id' => $this->school->id, 'name' => 'Siswa 1', 'email' => 's1@a.com', 'password' => 'x', 'role_id' => Role::where('name', 'siswa')->first()->id]);
        $student = Student::create(['school_id' => $this->school->id, 'user_id' => $studentUser->id, 'nisn' => '1']);
        
        $assignment = Assignment::create([
            'teacher_id' => Teacher::where('user_id', $this->guru->id)->first()->id,
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'title' => 'Tugas Matematika',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(2),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_path' => 'file.pdf',
            'score' => null, // unscored
        ]);

        $response = $this->actingAs($this->guru)->get(route('guru.dashboard'));
        $response->assertStatus(200);

        $response->assertSee('Anda memiliki 1 tugas siswa yang belum dinilai.');
        // Unscored tasks card should be 1
        $response->assertSee('Tugas Matematika');
        $response->assertSee('1 Pengumpulan Baru');
    }
}
