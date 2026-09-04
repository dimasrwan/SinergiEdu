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
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherFeedbackTest extends TestCase
{
    use RefreshDatabase;

    protected $school;
    protected $teacherUser;
    protected $teacher;
    protected $academicYear;
    protected $semester;
    protected $classroom;
    protected $subject;
    protected $assignment;
    protected $studentUser;
    protected $student;
    protected $submission;

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
            'nuptk' => '987654',
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
            'nisn' => '22222',
            'school_id' => $this->school->id,
        ]);

        StudentClass::create([
            'student_id' => $this->student->id,
            'class_id' => $this->classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'school_id' => $this->school->id,
        ]);

        $this->assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas 1',
            'description' => 'Deskripsi tugas 1',
            'deadline' => now()->addDays(7),
        ]);

        $this->submission = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $this->student->id,
            'file_path' => 'assignments/submissions/test.pdf',
            'notes' => 'Catatan siswa',
            'score' => null,
            'feedback' => null,
        ]);
    }

    // 1. Guru dapat melihat submission yang berhak diberi feedback
    public function test_guru_can_view_assignment_submissions_page()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.assignments.show', $this->assignment));
            
        $response->assertStatus(200);
        $response->assertSee('Beri Feedback');
    }

    // 2. Guru dapat membuat feedback
    // 3. Feedback tersimpan dengan owner yang benar
    public function test_guru_can_add_feedback()
    {
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => 'Bagus sekali!',
            ]);
            
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('assignment_submissions', [
            'id' => $this->submission->id,
            'feedback' => 'Bagus sekali!',
        ]);
    }

    // 4. Guru dapat mengedit feedback miliknya
    public function test_guru_can_edit_existing_feedback()
    {
        $this->submission->update(['feedback' => 'Old feedback']);
        
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => 'New feedback',
            ]);
            
        $response->assertRedirect();
        
        $this->assertDatabaseHas('assignment_submissions', [
            'id' => $this->submission->id,
            'feedback' => 'New feedback',
        ]);
    }

    // 5. Guru tidak dapat melihat feedback guru lain
    // 6. Guru tidak dapat membuat feedback pada submission guru lain
    public function test_guru_cannot_add_feedback_to_other_teachers_assignment()
    {
        $otherTeacherUser = User::create([
            'name' => 'Other', 'email' => 'other@t.com', 'password' => bcrypt('x'), 'role_id' => $this->teacherUser->role_id, 'school_id' => $this->school->id
        ]);
        Teacher::create(['user_id' => $otherTeacherUser->id, 'nip' => '33', 'school_id' => $this->school->id]);

        $response = $this->actingAs($otherTeacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => 'Hacked feedback!',
            ]);
            
        $response->assertStatus(403);
    }

    // 7. Cross-tenant access ditolak
    public function test_teacher_from_other_school_cannot_add_feedback()
    {
        $otherSchool = School::create(['name' => 'S2', 'npsn' => '22', 'is_active' => true]);
        $otherTeacherUser = User::create([
            'name' => 'O', 'email' => 'o@t.com', 'password' => bcrypt('x'), 'role_id' => $this->teacherUser->role_id, 'school_id' => $otherSchool->id
        ]);
        Teacher::create(['user_id' => $otherTeacherUser->id, 'nip' => '333', 'school_id' => $otherSchool->id]);

        $response = $this->actingAs($otherTeacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => 'Hack',
            ]);
            
        $response->assertStatus(403);
    }

    // 8. Feedback tidak mengubah nilai
    public function test_adding_feedback_does_not_change_score()
    {
        $this->submission->update(['score' => 85]);
        
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => 'Good job',
            ]);
            
        $this->assertDatabaseHas('assignment_submissions', [
            'id' => $this->submission->id,
            'feedback' => 'Good job',
            'score' => 85, // score should not change
        ]);
    }

    // 9. Duplicate feedback dicegah (karena satu row)
    public function test_feedback_is_updated_not_duplicated()
    {
        $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => 'F1',
            ]);
            
        $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => 'F2',
            ]);
            
        $this->assertEquals(1, AssignmentSubmission::where('id', $this->submission->id)->count());
        $this->assertEquals('F2', AssignmentSubmission::find($this->submission->id)->feedback);
    }

    // 10. Validation bekerja
    public function test_feedback_validation()
    {
        $longFeedback = str_repeat('A', 1500); // Exceeds max 1000
        
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => $longFeedback,
            ]);
            
        $response->assertSessionHasErrors('feedback');
    }

    // 11. Direct URL IDOR ditolak
    public function test_cannot_feedback_non_existent_submission()
    {
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, 9999]), [
                'feedback' => 'Feedback',
            ]);
            
        $response->assertStatus(404);
    }

    public function test_cannot_feedback_student_not_in_class()
    {
        $studentUser2 = User::create(['name' => 'S2', 'email' => 's2@test.com', 'password' => bcrypt('x'), 'role_id' => $this->studentUser->role_id, 'school_id' => $this->school->id]);
        $student2 = Student::create(['user_id' => $studentUser2->id, 'nis' => '12', 'school_id' => $this->school->id]);
        
        $submission2 = AssignmentSubmission::create([
            'assignment_id' => $this->assignment->id,
            'student_id' => $student2->id,
            'file_path' => 'assignments/submissions/test2.pdf',
        ]);
        
        // Student 2 is NOT in the classroom
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $submission2->id]), [
                'feedback' => 'Feedback',
            ]);
            
        $response->assertStatus(403);
    }

    // 12. Empty state benar
    public function test_can_delete_feedback_by_submitting_empty()
    {
        $this->submission->update(['feedback' => 'Old feedback']);
        
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$this->assignment->id, $this->submission->id]), [
                'feedback' => null,
            ]);
            
        $this->assertDatabaseHas('assignment_submissions', [
            'id' => $this->submission->id,
            'feedback' => null,
        ]);
    }

    // 13. Search/filter aman
    public function test_can_search_and_filter_submissions()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('guru.assignments.show', ['assignment' => $this->assignment->id, 'search' => 'Siswa Test']));
            
        $response->assertStatus(200);
        $response->assertSee('Siswa Test');
    }

    // 14. TenantIsolationTest tetap PASS
    public function test_guru_cannot_assign_feedback_to_other_assignment()
    {
        $otherAssignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas 2',
            'description' => 'Deskripsi tugas 2',
            'deadline' => now()->addDays(7),
        ]);
        
        $response = $this->actingAs($this->teacherUser)
            ->post(route('guru.assignments.submissions.feedback', [$otherAssignment->id, $this->submission->id]), [
                'feedback' => 'Bagus sekali!',
            ]);
            
        $response->assertStatus(403);
    }
}
