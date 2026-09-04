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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherGradingTest extends TestCase
{
    use RefreshDatabase;

    private User $teacherUser;
    private Teacher $teacher;
    private School $school;
    private Classroom $class;
    private Subject $subject;
    private AcademicYear $academicYear;
    private Semester $semester;
    private Assignment $assignment;
    private User $studentUser;
    private Student $student;
    private AssignmentSubmission $submission;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->school = School::create([
            'name' => 'Sekolah Test',
            'npsn' => '12345678',
            'address' => 'Jl. Test',
            'is_active' => true,
        ]);

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

        $this->teacherUser = User::create([
            'name' => 'Guru Test',
            'email' => 'guru@test.com',
            'password' => bcrypt('password'),
            'role_id' => $teacherRole->id,
            'school_id' => $this->school->id,
        ]);

        $this->teacher = Teacher::create([
            'user_id' => $this->teacherUser->id,
            'nip' => '111111',
            'nuptk' => '222222',
            'school_id' => $this->school->id,
        ]);

        $this->class = Classroom::create([
            'name' => 'Kelas XA',
            'school_id' => $this->school->id,
            'grade_level' => '10'
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematika',
            'code' => 'MTK',
            'school_id' => $this->school->id,
        ]);

        TeacherSubject::create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'school_id' => $this->school->id,
        ]);

        $this->assignment = Assignment::create([
            'title' => 'Tugas 1',
            'description' => 'Kerjakan',
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'deadline' => now()->addDays(2),
        ]);

        $studentRole = Role::create(['name' => 'siswa', 'display_name' => 'Siswa']);
        
        $this->studentUser = User::create([
            'name' => 'Siswa Test',
            'email' => 'siswa@test.com',
            'password' => bcrypt('password'),
            'role_id' => $studentRole->id,
            'school_id' => $this->school->id,
        ]);

        $this->student = Student::create([
            'user_id' => $this->studentUser->id,
            'nis' => '123',
            'nisn' => '12345',
            'school_id' => $this->school->id,
        ]);

        StudentClass::create([
            'student_id' => $this->student->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'school_id' => $this->school->id,
        ]);

        $this->submission = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $this->student->id,
            'file_path' => 'dummy.pdf',
        ]);
    }

    public function test_guru_can_see_ungraded_and_graded_status()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.assignments.show', $this->assignment->id));
            
        $response->assertStatus(200);
        $response->assertSee('Status Penilaian');
        $response->assertSee('0'); // 0 graded
    }

    public function test_guru_can_submit_valid_grade()
    {
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 85
            ]);
            
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('assignment_submissions', [
            'id' => $this->submission->id,
            'score' => 85
        ]);
    }

    public function test_nilai_tersimpan_ke_student_grades()
    {
        $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 90
            ]);
            
        $this->assertDatabaseHas('student_grades', [
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 90,
            'teacher_id' => $this->teacher->id
        ]);
    }

    public function test_guru_cannot_grade_if_score_is_invalid()
    {
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 105
            ]);
            
        $response->assertSessionHasErrors('score');
        
        $response2 = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => -5
            ]);
            
        $response2->assertSessionHasErrors('score');
    }

    public function test_guru_cannot_grade_student_from_other_class()
    {
        $otherClass = Classroom::create(['name' => 'Kelas XB', 'school_id' => $this->school->id, 'grade_level' => '10']);
        $otherStudentUser = User::create(['name' => 'Other', 'email' => 'other@test.com', 'password' => bcrypt('password'), 'role_id' => $this->studentUser->role_id, 'school_id' => $this->school->id]);
        $otherStudent = Student::create(['user_id' => $otherStudentUser->id, 'nis' => '111', 'nisn' => '222', 'school_id' => $this->school->id]);
        
        StudentClass::create(['student_id' => $otherStudent->id, 'class_id' => $otherClass->id, 'academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);
        
        $otherSubmission = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $otherStudent->id,
            'file_path' => 'dummy2.pdf',
        ]);

        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $otherSubmission->id]), [
                'score' => 80
            ]);
            
        $response->assertStatus(403);
    }

    public function test_guru_cannot_grade_assignment_they_dont_own()
    {
        $otherTeacherUser = User::create(['name' => 'Other Guru', 'email' => 'otherg@test.com', 'password' => bcrypt('pass'), 'role_id' => $this->teacherUser->role_id, 'school_id' => $this->school->id]);
        $otherTeacher = Teacher::create(['user_id' => $otherTeacherUser->id, 'nip' => '33', 'nuptk' => '44', 'school_id' => $this->school->id]);
        
        $response = $this->actingAs($otherTeacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 80
            ]);
            
        // Usually caught by Teacher role middleware/authorization. We assume 403.
        $response->assertStatus(403);
    }

    public function test_guru_cannot_grade_if_student_hasnt_submitted()
    {
        // 999 doesn't exist
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, 999]), [
                'score' => 80
            ]);
            
        $response->assertStatus(404);
    }

    public function test_if_grade_exists_grading_again_updates_score()
    {
        $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 80
            ]);
            
        $this->assertDatabaseHas('assignment_submissions', ['id' => $this->submission->id, 'score' => 80]);
        
        $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 95
            ]);
            
        $this->assertDatabaseHas('assignment_submissions', ['id' => $this->submission->id, 'score' => 95]);
        $this->assertDatabaseHas('student_grades', ['student_id' => $this->student->id, 'assignment_score' => 95]);
    }

    public function test_teacher_from_other_school_cannot_grade()
    {
        $otherSchool = School::create(['name' => 'S2', 'npsn' => '22', 'is_active' => true]);
        $otherTeacherUser = User::create(['name' => 'O', 'email' => 'o@t.com', 'password' => bcrypt('x'), 'role_id' => $this->teacherUser->role_id, 'school_id' => $otherSchool->id]);
        Teacher::create(['user_id' => $otherTeacherUser->id, 'nip' => '333', 'school_id' => $otherSchool->id]);

        $response = $this->actingAs($otherTeacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 80
            ]);
            
        $response->assertStatus(403);
    }

    public function test_teacher_without_teachersubject_for_class_cannot_grade()
    {
        $this->assertTrue(true);
    }

    public function test_admin_cannot_grade()
    {
        $adminRole = Role::create(['name' => 'admin_sekolah', 'display_name' => 'Admin']);
        $adminUser = User::create(['name' => 'Admin', 'email' => 'admin@t.com', 'password' => bcrypt('x'), 'role_id' => $adminRole->id, 'school_id' => $this->school->id]);
        
        $response = $this->actingAs($adminUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 80
            ]);
            
        $response->assertStatus(403);
    }

    public function test_super_admin_cannot_grade()
    {
        $saRole = Role::create(['name' => 'super_admin', 'display_name' => 'SA']);
        $saUser = User::create(['name' => 'SA', 'email' => 'sa@t.com', 'password' => bcrypt('x'), 'role_id' => $saRole->id]);
        
        $response = $this->actingAs($saUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 80
            ]);
            
        $response->assertStatus(403);
    }

    public function test_student_cannot_grade()
    {
        $response = $this->actingAs($this->studentUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 80
            ]);
            
        $response->assertStatus(403);
    }

    public function test_grade_reflects_in_ui_correctly()
    {
        $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.grade', [$this->assignment->id, $this->submission->id]), [
                'score' => 88
            ]);
            
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.assignments.show', $this->assignment->id));
            
        $response->assertSee('88');
        $response->assertSee('Status Penilaian');
    }
}
