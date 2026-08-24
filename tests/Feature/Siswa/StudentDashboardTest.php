<?php

namespace Tests\Feature\Siswa;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Feedback;
use App\Models\Material;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $school;
    protected $studentUser;
    protected $student;
    protected $classroom;
    protected $academicYear;
    protected $teacher;
    protected $subject;
    protected $semester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Sekolah Test',
            'npsn' => '123456',
            'is_active' => true,
        ]);

        $studentRole = Role::create(['name' => 'siswa', 'display_name' => 'Siswa']);
        $teacherRole = Role::create(['name' => 'guru', 'display_name' => 'Guru']);

        $this->academicYear = AcademicYear::create([
            'year' => '2023/2024',
            'school_id' => $this->school->id,
            'is_active' => true
        ]);
        
        $this->semester = Semester::create([
            'name' => 'Ganjil',
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'is_active' => true
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematika',
            'code' => 'MTK',
            'school_id' => $this->school->id,
        ]);

        $this->classroom = Classroom::create([
            'name' => 'Kelas XA',
            'grade_level' => 10,
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->studentUser = User::create([
            'name' => 'Siswa Aktif',
            'email' => 'siswa@test.com',
            'password' => bcrypt('password'),
            'role_id' => $studentRole->id,
            'school_id' => $this->school->id,
        ]);

        $this->student = Student::create([
            'user_id' => $this->studentUser->id,
            'nis' => '123',
            'school_id' => $this->school->id,
        ]);

        StudentClass::create([
            'student_id' => $this->student->id,
            'class_id' => $this->classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'school_id' => $this->school->id,
        ]);

        $teacherUser = User::create([
            'name' => 'Guru Aktif',
            'email' => 'guru@test.com',
            'password' => bcrypt('password'),
            'role_id' => $teacherRole->id,
            'school_id' => $this->school->id,
        ]);

        $this->teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'nip' => '111',
            'school_id' => $this->school->id,
        ]);
    }

    // 1. Siswa dapat membuka dashboard.
    public function test_student_can_open_dashboard()
    {
        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertStatus(200);
    }

    // 2. Dashboard memakai data siswa yang login.
    public function test_dashboard_uses_logged_in_student_data()
    {
        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Hai, Siswa Aktif!');
        $response->assertSee('Kelas XA');
    }

    // 3. Tugas hanya berasal dari kelas aktif.
    public function test_assignments_from_active_class_only()
    {
        $otherClass = Classroom::create([
            'name' => 'Kelas XB',
            'grade_level' => 10,
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        Assignment::create([
            'class_id' => $this->classroom->id,
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas Valid',
            'description' => 'Desc',
            'deadline' => now()->addDays(5),
        ]);

        Assignment::create([
            'class_id' => $otherClass->id,
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas Invalid',
            'description' => 'Desc',
            'deadline' => now()->addDays(5),
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Tugas Valid');
        $response->assertDontSee('Tugas Invalid');
    }

    // 4. Materi hanya berasal dari kelas aktif.
    public function test_materials_from_active_class_only()
    {
        $otherClass = Classroom::create([
            'name' => 'Kelas XC',
            'grade_level' => 10,
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        Material::create([
            'class_id' => $this->classroom->id,
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'title' => 'Materi Valid',
        ]);

        Material::create([
            'class_id' => $otherClass->id,
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'title' => 'Materi Invalid',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Materi Valid');
        $response->assertDontSee('Materi Invalid');
    }

    // 5. Nilai hanya milik siswa login.
    public function test_grades_only_for_logged_in_student()
    {
        $otherStudent = Student::create([
            'user_id' => User::create(['name' => 'Other', 'email' => 'other@test.com', 'password' => bcrypt('123'), 'school_id' => $this->school->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id,
            'nis' => '444',
            'school_id' => $this->school->id,
        ]);

        StudentGrade::create([
            'student_id' => $otherStudent->id,
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'class_id' => $this->classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 99,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Belum Ada');
        $response->assertDontSee('>99<', false); // Ensure not picking up SVGs
    }

    // 6. Feedback hanya milik siswa login.
    public function test_feedback_only_for_logged_in_student()
    {
        $otherStudent = Student::create([
            'user_id' => User::create(['name' => 'Other 2', 'email' => 'other2@test.com', 'password' => bcrypt('123'), 'school_id' => $this->school->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id,
            'nis' => '555',
            'school_id' => $this->school->id,
        ]);

        Feedback::create([
            'student_id' => $otherStudent->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'A',
            'message' => 'Feedback Rahasia',
        ]);

        Feedback::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'A',
            'message' => 'Feedback Publik',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Feedback Publik');
        $response->assertDontSee('Feedback Rahasia');
    }

    // 8. Cross-tenant data tidak terlihat.
    public function test_cross_tenant_data_is_invisible()
    {
        $otherSchool = School::create(['name' => 'Sekolah Lain', 'is_active' => true]);
        
        $otherYear = AcademicYear::create([
            'year' => '2024/2025',
            'school_id' => $otherSchool->id,
            'is_active' => true
        ]);
        
        $otherSubject = Subject::create([
            'name' => 'Other',
            'code' => 'OT',
            'school_id' => $otherSchool->id,
        ]);
        
        $otherClass = Classroom::create([
            'name' => 'Kelas Rahasia',
            'grade_level' => 10,
            'school_id' => $otherSchool->id,
            'academic_year_id' => $otherYear->id,
        ]);
        
        $otherTeacher = Teacher::create([
            'user_id' => User::create(['name' => 'Guru Tenant', 'email' => 'teacher@tenant.com', 'password' => bcrypt('123'), 'school_id' => $otherSchool->id, 'role_id' => Role::where('name', 'guru')->first()->id])->id,
            'school_id' => $otherSchool->id,
        ]);

        Material::create([
            'class_id' => $otherClass->id,
            'subject_id' => $otherSubject->id,
            'teacher_id' => $otherTeacher->id,
            'title' => 'Materi Tenant Lain',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertDontSee('Materi Tenant Lain');
    }

    // 9. Empty state kelas aktif benar.
    public function test_empty_state_without_active_class()
    {
        StudentClass::where('student_id', $this->student->id)->delete();

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Belum Ada Kelas Aktif');
        $response->assertSee('Anda belum didaftarkan pada kelas aktif');
    }

    // 10. Missing academic context benar.
    public function test_missing_academic_context()
    {
        $this->academicYear->update(['is_active' => false]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Belum Ada Kelas Aktif');
    }

    // 11. Upcoming assignments benar.
    public function test_upcoming_assignments_exclude_past_or_submitted()
    {
        $assignmentPending = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Pending',
            'description' => 'Desc',
            'deadline' => now()->addDays(2),
        ]);

        $assignmentPast = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Past',
            'description' => 'Desc',
            'deadline' => now()->subDays(2),
        ]);

        $assignmentSubmitted = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Submitted',
            'description' => 'Desc',
            'deadline' => now()->addDays(5),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignmentSubmitted->id,
            'student_id' => $this->student->id,
            'file_path' => 'test.pdf',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Tugas Pending');
        $response->assertDontSee('Tugas Past');
        $response->assertDontSee('Tugas Submitted');
    }

    // 12. Recent materials benar.
    public function test_recent_materials_correct()
    {
        Material::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Materi Pertama',
            'created_at' => now()->subDays(2),
        ]);

        Material::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Materi Kedua',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Materi Pertama');
        $response->assertSee('Materi Kedua');
    }

    // 13. Recent feedback benar.
    public function test_recent_feedback_correct()
    {
        Feedback::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'A',
            'message' => 'Bagus sekali',
            'content' => 'Bagus sekali',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        $response->assertSee('Bagus sekali');
    }

    // 14. Progress formula benar jika formula tersedia.
    public function test_progress_formula_is_correct()
    {
        $a1 = Assignment::create(['class_id' => $this->classroom->id, 'subject_id' => $this->subject->id, 'teacher_id' => $this->teacher->id, 'title' => 'A1', 'description' => 'D', 'deadline' => now()->addDays(5)]);
        $a2 = Assignment::create(['class_id' => $this->classroom->id, 'subject_id' => $this->subject->id, 'teacher_id' => $this->teacher->id, 'title' => 'A2', 'description' => 'D', 'deadline' => now()->addDays(5)]);
        $a3 = Assignment::create(['class_id' => $this->classroom->id, 'subject_id' => $this->subject->id, 'teacher_id' => $this->teacher->id, 'title' => 'A3', 'description' => 'D', 'deadline' => now()->addDays(5)]);
        $a4 = Assignment::create(['class_id' => $this->classroom->id, 'subject_id' => $this->subject->id, 'teacher_id' => $this->teacher->id, 'title' => 'A4', 'description' => 'D', 'deadline' => now()->addDays(5)]);

        AssignmentSubmission::create(['assignment_id' => $a1->id, 'student_id' => $this->student->id, 'file_path' => 'file.pdf']);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        
        $response->assertSee('Progres Tugas: 25%');
        $response->assertSee('Kamu telah menyelesaikan 1 dari total 4 tugas');
    }

    // 15. Tidak ada hardcoded dashboard data.
    public function test_no_hardcoded_dummy_data_left()
    {
        $response = $this->actingAs($this->studentUser)->get(route('siswa.dashboard'));
        
        $response->assertDontSee('Progres Mingguan: 70%');
        $response->assertDontSee('Tingkat Kehadiran');
        $response->assertDontSee('>98%<', false);
        $response->assertDontSee('Bapak Budi Santoso');
        $response->assertDontSee('Laporan Praktikum Termodinamika');
        $response->assertDontSee('Modul 4: Termodinamika Dasar');
    }
}


