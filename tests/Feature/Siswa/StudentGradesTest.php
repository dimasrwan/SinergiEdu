<?php

namespace Tests\Feature\Siswa;

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

class StudentGradesTest extends TestCase
{
    use RefreshDatabase;

    protected $school;
    protected $studentUser;
    protected $student;
    protected $classroom;
    protected $academicYear;
    protected $semester;
    protected $teacher;
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create(['name' => 'Sekolah A', 'npsn' => '123456', 'is_active' => true]);
        
        $studentRole = Role::create(['name' => 'siswa', 'display_name' => 'Siswa']);
        $teacherRole = Role::create(['name' => 'guru', 'display_name' => 'Guru']);

        $this->academicYear = AcademicYear::create(['year' => '2023/2024', 'school_id' => $this->school->id, 'is_active' => true]);
        $this->semester = Semester::create(['name' => 'Ganjil', 'school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id, 'is_active' => true]);
        $this->subject = Subject::create(['name' => 'Fisika', 'code' => 'FSK', 'school_id' => $this->school->id]);

        $this->classroom = Classroom::create(['name' => 'Kelas XA', 'grade_level' => 10, 'school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id]);

        $this->studentUser = User::create(['name' => 'Siswa 1', 'email' => 'siswa1@test.com', 'password' => bcrypt('password'), 'role_id' => $studentRole->id, 'school_id' => $this->school->id]);
        $this->student = Student::create(['user_id' => $this->studentUser->id, 'nis' => '111', 'school_id' => $this->school->id]);
        StudentClass::create(['student_id' => $this->student->id, 'class_id' => $this->classroom->id, 'academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $teacherUser = User::create(['name' => 'Guru A', 'email' => 'guru1@test.com', 'password' => bcrypt('password'), 'role_id' => $teacherRole->id, 'school_id' => $this->school->id]);
        $this->teacher = Teacher::create(['user_id' => $teacherUser->id, 'nip' => 'T1', 'school_id' => $this->school->id]);

        TeacherSubject::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'school_id' => $this->school->id,
        ]);
    }

    public function test_student_can_see_their_own_grades_index()
    {
        StudentGrade::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 85,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.index'));
        $response->assertStatus(200);
        $response->assertSee('Fisika');
        $response->assertSee('85');
    }

    public function test_student_cannot_see_other_students_grades_in_index()
    {
        $otherStudent = Student::create(['user_id' => User::create(['name' => 'S2', 'email' => 's2@test.com', 'password' => bcrypt('123'), 'school_id' => $this->school->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id, 'nis' => '222', 'school_id' => $this->school->id]);
        StudentGrade::create([
            'student_id' => $otherStudent->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 88, // Unique score
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.index'));
        $response->assertDontSee('88');
    }

    public function test_student_can_view_grade_detail()
    {
        $grade = StudentGrade::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 90,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.show', $grade));
        $response->assertStatus(200);
        $response->assertSee('Fisika');
        $response->assertSee('Rincian Tugas');
    }

    public function test_student_cannot_view_other_students_grade_detail_idor()
    {
        $otherStudent = Student::create(['user_id' => User::create(['name' => 'S2', 'email' => 's2@test.com', 'password' => bcrypt('123'), 'school_id' => $this->school->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id, 'nis' => '222', 'school_id' => $this->school->id]);
        $otherGrade = StudentGrade::create([
            'student_id' => $otherStudent->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 100,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.show', $otherGrade));
        $response->assertStatus(403);
    }

    public function test_cross_tenant_grade_detail_is_invisible()
    {
        $otherSchool = School::create(['name' => 'Sekolah B', 'is_active' => true]);
        $otherStudent = Student::create(['user_id' => User::create(['name' => 'S3', 'email' => 's3@test.com', 'password' => bcrypt('123'), 'school_id' => $otherSchool->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id, 'nis' => '333', 'school_id' => $otherSchool->id]);
        $otherYear = AcademicYear::create(['year' => 'X', 'school_id' => $otherSchool->id, 'is_active' => true]);
        $otherSemester = Semester::create(['name' => 'Y', 'school_id' => $otherSchool->id, 'academic_year_id' => $otherYear->id, 'is_active' => true]);
        
        $otherClass = Classroom::create(['name' => 'XB', 'grade_level' => 10, 'school_id' => $otherSchool->id, 'academic_year_id' => $otherYear->id]);
        $otherSubject = Subject::create(['name' => 'B', 'code' => 'B', 'school_id' => $otherSchool->id]);
        $otherTeacher = Teacher::create(['user_id' => User::create(['name' => 'B', 'email' => 'ba@test.com', 'password' => bcrypt('123'), 'school_id' => $otherSchool->id, 'role_id' => Role::where('name', 'guru')->first()->id])->id, 'nip' => '9', 'school_id' => $otherSchool->id]);
        
        $otherGrade = StudentGrade::create([
            'student_id' => $otherStudent->id,
            'teacher_id' => $otherTeacher->id,
            'class_id' => $otherClass->id,
            'subject_id' => $otherSubject->id,
            'academic_year_id' => $otherYear->id,
            'semester_id' => $otherSemester->id,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.show', $otherGrade));
        $response->assertStatus(403); // It throws 403 because it belongs to another student
    }

    public function test_ungraded_task_shows_as_menunggu_penilaian()
    {
        $grade = StudentGrade::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Menunggu',
            'description' => 'Desc',
            'deadline' => now()->addDay(),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'file_path' => 'dummy.pdf',
            // no score
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.show', $grade));
        $response->assertSee('Menunggu Penilaian');
        $response->assertDontSee('Belum Mengumpulkan');
    }

    public function test_unsubmitted_task_shows_as_belum_mengumpulkan()
    {
        $grade = StudentGrade::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Bolong',
            'description' => 'Desc',
            'deadline' => now()->addDay(),
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.show', $grade));
        $response->assertSee('Belum Mengumpulkan');
    }

    public function test_feedback_is_displayed_if_available()
    {
        $grade = StudentGrade::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Feedback',
            'description' => 'Desc',
            'deadline' => now()->addDay(),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'score' => 95,
            'feedback' => 'Mantap Gan',
            'file_path' => 'dummy.pdf',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.show', $grade));
        $response->assertSee('Dinilai');
        $response->assertSee('95');
        $response->assertSee('Mantap Gan');
    }

    public function test_assignment_of_other_student_not_mixed_up()
    {
        $grade = StudentGrade::create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Beda Orang',
            'description' => 'Desc',
            'deadline' => now()->addDay(),
        ]);

        // Temannya mengumpulkan dan dapat 100
        $otherStudent = Student::create(['user_id' => User::create(['name' => 'S2', 'email' => 's2@test.com', 'password' => bcrypt('123'), 'school_id' => $this->school->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id, 'nis' => '222', 'school_id' => $this->school->id]);
        
        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $otherStudent->id,
            'score' => 88,
            'file_path' => 'dummy.pdf',
        ]);

        // Siswa 1 belum mengumpulkan
        $response = $this->actingAs($this->studentUser)->get(route('siswa.grades.show', $grade));
        $response->assertSee('Belum Mengumpulkan');
        $response->assertDontSee('88'); // Jangan sampai lihat nilai temannya
    }
}
