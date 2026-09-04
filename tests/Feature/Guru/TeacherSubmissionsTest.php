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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherSubmissionsTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;
    
    private User $teacherA;
    private Teacher $teacherProfileA;
    
    private User $teacherB;
    private Teacher $teacherProfileB;

    private AcademicYear $academicYear;
    private Semester $semester;
    private Classroom $classA;
    private Subject $subjectA;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Tenant (School)
        $this->schoolA = School::create(['name' => 'School A', 'domain' => 'schoola.com']);
        $this->schoolB = School::create(['name' => 'School B', 'domain' => 'schoolb.com']);

        // 2. Setup Context
        $this->academicYear = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => true]);
        $this->semester = Semester::create(['school_id' => $this->schoolA->id, 'academic_year_id' => $this->academicYear->id, 'name' => 'Ganjil', 'is_active' => true]);

        // 3. Setup Class & Subject
        $this->classA = Classroom::create(['school_id' => $this->schoolA->id, 'name' => 'X IPA 1', 'grade_level' => '10']);
        $this->subjectA = Subject::create(['school_id' => $this->schoolA->id, 'name' => 'Matematika', 'code' => 'MAT']);

        // 4. Setup Teacher A
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        $role = Role::where('name', 'guru')->first();

        $this->teacherA = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru A',
            'email' => 'guru_a@s.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);
        $this->teacherProfileA = Teacher::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $this->teacherA->id,
            'nip' => '111',
        ]);
        
        // Assign Guru A to X IPA 1 - Matematika
        TeacherSubject::create([
            'school_id' => $this->schoolA->id,
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        // 5. Setup Teacher B (Same School)
        $this->teacherB = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru B',
            'email' => 'guru_b@s.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);
        $this->teacherProfileB = Teacher::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $this->teacherB->id,
            'nip' => '222',
        ]);
    }

    private function createStudent(School $school, Classroom $classroom, string $name, string $email, string $nis): Student
    {
        Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $role = Role::where('name', 'siswa')->first();

        $user = User::create([
            'school_id' => $school->id,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);
        
        $student = Student::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'nis' => $nis,
        ]);

        StudentClass::create([
            'school_id' => $school->id,
            'student_id' => $student->id,
            'class_id' => $classroom->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        return $student;
    }

    public function test_guru_can_view_student_list_in_assignment()
    {
        // Add 2 students
        $student1 = $this->createStudent($this->schoolA, $this->classA, 'Siswa Satu', 's1@s.com', '101');
        $student2 = $this->createStudent($this->schoolA, $this->classA, 'Siswa Dua', 's2@s.com', '102');

        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Mat 1',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(3),
        ]);

        $this->actingAs($this->teacherA);
        
        $response = $this->get(route('guru.assignments.show', $assignment));
        
        $response->assertStatus(200);
        $response->assertSee('Siswa Satu');
        $response->assertSee('Siswa Dua');
        $response->assertSee('Belum Mengumpulkan');
        $response->assertSee('0', false);
        $response->assertSee('/ 2', false);
    }

    public function test_guru_can_see_submitted_status_and_summary_correctly()
    {
        $student1 = $this->createStudent($this->schoolA, $this->classA, 'Siswa Satu', 's1@s.com', '101');
        $student2 = $this->createStudent($this->schoolA, $this->classA, 'Siswa Dua', 's2@s.com', '102');

        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Mat 1',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(3),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student1->id,
            'file_path' => 'dummy.pdf',
            'notes' => 'Ini jawaban',
        ]);

        $this->actingAs($this->teacherA);
        
        $response = $this->get(route('guru.assignments.show', $assignment));
        
        $response->assertStatus(200);
        $response->assertSee('Sudah Mengumpulkan');
        $response->assertSee('1', false);
        $response->assertSee('/ 2', false);
    }

    public function test_late_submission_detection()
    {
        $student1 = $this->createStudent($this->schoolA, $this->classA, 'Siswa Satu', 's1@s.com', '101');

        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Mat 1',
            'description' => 'Kerjakan',
            'deadline' => now()->subDays(1), // Deadline in the past
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student1->id,
            'file_path' => 'dummy.pdf',
            'notes' => 'Ini jawaban telat',
        ]);

        $this->actingAs($this->teacherA);
        
        $response = $this->get(route('guru.assignments.show', $assignment));
        
        $response->assertStatus(200);
        $response->assertSee('Terlambat');
    }

    public function test_search_and_filter_submissions()
    {
        $student1 = $this->createStudent($this->schoolA, $this->classA, 'Fauzan', 's1@s.com', '101');
        $student2 = $this->createStudent($this->schoolA, $this->classA, 'Rahma', 's2@s.com', '102');

        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Mat 1',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(3),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student1->id,
            'file_path' => 'dummy.pdf',
        ]);

        $this->actingAs($this->teacherA);
        
        // Search
        $response = $this->get(route('guru.assignments.show', ['assignment' => $assignment->id, 'search' => 'Fauzan']));
        $response->assertSee('Fauzan');
        $response->assertDontSee('Rahma');

        // Filter submitted
        $response = $this->get(route('guru.assignments.show', ['assignment' => $assignment->id, 'status' => 'submitted']));
        $response->assertSee('Fauzan');
        $response->assertDontSee('Rahma');

        // Filter not submitted
        $response = $this->get(route('guru.assignments.show', ['assignment' => $assignment->id, 'status' => 'not_submitted']));
        $response->assertSee('Rahma');
        $response->assertDontSee('Fauzan');
    }

    public function test_guru_cannot_view_other_teachers_assignment_submissions()
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Mat 1',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(3),
        ]);

        $this->actingAs($this->teacherB);
        
        $response = $this->get(route('guru.assignments.show', $assignment));
        $response->assertStatus(403);
    }

    public function test_proxy_download_security()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('jawaban.pdf', 100);
        $path = $file->store('submissions', 'local');

        $student1 = $this->createStudent($this->schoolA, $this->classA, 'Fauzan', 's1@s.com', '101');

        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Mat 1',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(3),
        ]);

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student1->id,
            'file_path' => $path,
        ]);

        // Guru A should be able to download
        $this->actingAs($this->teacherA);
        $response = $this->get(route('guru.assignments.submissions.download', [$assignment, $submission]));
        $response->assertStatus(200);

        // Guru B should not be able to download (403)
        $this->actingAs($this->teacherB);
        $response = $this->get(route('guru.assignments.submissions.download', [$assignment, $submission]));
        $response->assertStatus(403);
    }
}
