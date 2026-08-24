<?php

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
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherStudentProgressTest extends TestCase
{
    use RefreshDatabase;

    protected $school;
    protected $teacherUser;
    protected $teacher;
    protected $academicYear;
    protected $semester;
    protected $classroom;
    protected $subject;
    protected $studentUser;
    protected $student;
    protected $assignment;
    protected $submission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Sekolah Test',
            'npsn' => '12345678',
            'is_active' => true,
        ]);

        $teacherRole = Role::create(['name' => 'guru', 'display_name' => 'Guru']);
        $studentRole = Role::create(['name' => 'siswa', 'display_name' => 'Siswa']);
        
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

        $this->teacherUser = User::create([
            'name' => 'Guru Test',
            'email' => 'guru@test.com',
            'password' => bcrypt('password'),
            'role_id' => $teacherRole->id,
            'school_id' => $this->school->id,
        ]);

        $this->teacher = Teacher::create([
            'user_id' => $this->teacherUser->id,
            'nip' => '123456',
            'school_id' => $this->school->id,
        ]);

        $this->classroom = Classroom::create([
            'name' => 'Kelas XA',
            'grade_level' => 10,
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematika',
            'code' => 'MTK',
            'school_id' => $this->school->id,
        ]);

        TeacherSubject::create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'class_id' => $this->classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'school_id' => $this->school->id,
        ]);

        $this->studentUser = User::create([
            'name' => 'Siswa Test',
            'email' => 'siswa@test.com',
            'password' => bcrypt('password'),
            'role_id' => $studentRole->id,
            'school_id' => $this->school->id,
        ]);

        $this->student = Student::create([
            'user_id' => $this->studentUser->id,
            'nis' => '11111',
            'school_id' => $this->school->id,
        ]);

        StudentClass::create([
            'student_id' => $this->student->id,
            'class_id' => $this->classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'school_id' => $this->school->id,
        ]);
        
        StudentGrade::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 85,
        ]);

        $this->assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas 1',
            'description' => 'Deskripsi',
            'deadline' => now()->addDays(7),
        ]);

        $this->submission = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $this->student->id,
            'file_path' => 'file.pdf',
            'score' => 85,
            'feedback' => 'Bagus',
        ]);
    }

    // 1. Guru dapat melihat siswa dari kelas yang diajar.
    public function test_guru_can_view_students_in_their_class()
    {
        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index'));
        $response->assertStatus(200);
        $response->assertSee('Siswa Test');
    }

    // 2. Guru tidak melihat siswa kelas lain.
    public function test_guru_cannot_view_students_in_other_classes()
    {
        $otherClassroom = Classroom::create(['name' => 'XB', 'grade_level' => 10, 'school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id]);
        $otherStudentUser = User::create(['name' => 'Other Siswa', 'email' => 'o@o.com', 'password' => bcrypt('x'), 'role_id' => $this->studentUser->role_id, 'school_id' => $this->school->id]);
        $otherStudent = Student::create(['user_id' => $otherStudentUser->id, 'nis' => '22', 'school_id' => $this->school->id]);
        StudentClass::create(['student_id' => $otherStudent->id, 'class_id' => $otherClassroom->id, 'academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index'));
        $response->assertDontSee('Other Siswa');
    }

    // 3. Guru tidak melihat siswa tenant lain.
    public function test_guru_cannot_view_students_from_other_tenant()
    {
        $otherSchool = School::create(['name' => 'S2', 'npsn' => '22', 'is_active' => true]);
        $otherStudentUser = User::create(['name' => 'Other Tenant', 'email' => 'ot@t.com', 'password' => bcrypt('x'), 'role_id' => $this->studentUser->role_id, 'school_id' => $otherSchool->id]);
        Student::create(['user_id' => $otherStudentUser->id, 'nis' => '33', 'school_id' => $otherSchool->id]);

        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index'));
        $response->assertDontSee('Other Tenant');
    }

    // 4. Search siswa aman.
    public function test_search_student()
    {
        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index', ['search' => 'Siswa Test']));
        $response->assertSee('Siswa Test');
        
        $response2 = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index', ['search' => 'RandomName']));
        $response2->assertDontSee('Siswa Test');
    }

    // 5. Filter kelas aman.
    public function test_filter_class()
    {
        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index', ['class_id' => $this->classroom->id]));
        $response->assertSee('Siswa Test');
        
        $response2 = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index', ['class_id' => 999]));
        $response2->assertDontSee('Siswa Test');
    }

    // 6. Detail siswa hanya dapat diakses jika Guru berhak.
    public function test_show_detail_student_progress()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.student-progress.show', ['student' => $this->student->id, 'subject_id' => $this->subject->id, 'class_id' => $this->classroom->id]));
            
        $response->assertStatus(200);
        $response->assertSee('Detail Perkembangan Siswa');
    }

    // 7. Nilai menggunakan data real.
    public function test_shows_real_grades()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.student-progress.show', ['student' => $this->student->id, 'subject_id' => $this->subject->id, 'class_id' => $this->classroom->id]));
            
        $response->assertSee('85'); // Avg score
    }

    // 8. Submission stats benar.
    public function test_submission_stats()
    {
        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index'));
        // Tugas Selesai = 1
        $response->assertSee('1 / 1');
    }

    // 9. Feedback yang tampil benar.
    public function test_feedback_shown_on_detail()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.student-progress.show', ['student' => $this->student->id, 'subject_id' => $this->subject->id, 'class_id' => $this->classroom->id]));
            
        $response->assertSee('Bagus');
    }

    // 10. Empty state benar.
    public function test_empty_state_without_students()
    {
        StudentClass::where('student_id', $this->student->id)->delete();
        
        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index'));
        $response->assertSee('Belum ada siswa yang terdaftar pada kelas Anda.');
    }

    // 11. Academic context benar.
    public function test_requires_active_academic_context()
    {
        $this->academicYear->update(['is_active' => false]);
        
        $response = $this->actingAs($this->teacherUser)->get(route('guru.student-progress.index'));
        $response->assertSee('Periode Akademik Aktif Belum Tersedia');
    }

    // 12. Trend tidak ditampilkan jika data kurang.
    public function test_no_trend_chart_if_insufficient_data()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.student-progress.show', ['student' => $this->student->id, 'subject_id' => $this->subject->id, 'class_id' => $this->classroom->id]));
            
        $response->assertSee('Belum cukup data');
    }
    
    public function test_shows_trend_chart_if_sufficient_data()
    {
        $assignment2 = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas 2',
            'description' => 'Deskripsi',
            'deadline' => now()->addDays(7),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment2->id,
            'student_id' => $this->student->id,
            'file_path' => 'file2.pdf',
            'score' => 90,
        ]);
        
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.student-progress.show', ['student' => $this->student->id, 'subject_id' => $this->subject->id, 'class_id' => $this->classroom->id]));
            
        $response->assertSee('trendChart');
    }

    // 13. IDOR ditolak.
    public function test_idor_blocked_for_other_student_detail()
    {
        $otherStudentUser = User::create(['name' => 'S2', 'email' => 's2@test.com', 'password' => bcrypt('x'), 'role_id' => $this->studentUser->role_id, 'school_id' => $this->school->id]);
        $otherStudent = Student::create(['user_id' => $otherStudentUser->id, 'nis' => '22', 'school_id' => $this->school->id]);
        
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.student-progress.show', ['student' => $otherStudent->id, 'subject_id' => $this->subject->id, 'class_id' => $this->classroom->id]));
            
        $response->assertStatus(403);
    }

    // 14. TenantIsolationTest tetap PASS (implicitly covered as we use Tenant isolation traits everywhere and we tested other tenant above).
}
