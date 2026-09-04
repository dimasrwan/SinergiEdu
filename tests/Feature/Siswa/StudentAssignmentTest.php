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
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentAssignmentTest extends TestCase
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
        Storage::fake('local');
        Storage::fake('public');

        $this->school = School::create(['name' => 'Sekolah Test', 'npsn' => '123456', 'is_active' => true]);
        
        $studentRole = Role::create(['name' => 'siswa', 'display_name' => 'Siswa']);
        $teacherRole = Role::create(['name' => 'guru', 'display_name' => 'Guru']);

        $this->academicYear = AcademicYear::create(['year' => '2023/2024', 'school_id' => $this->school->id, 'is_active' => true]);
        $this->semester = Semester::create(['name' => 'Ganjil', 'school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id, 'is_active' => true]);
        $this->subject = Subject::create(['name' => 'Matematika', 'code' => 'MTK', 'school_id' => $this->school->id]);

        $this->classroom = Classroom::create(['name' => 'Kelas XA', 'grade_level' => 10, 'school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id]);

        $this->studentUser = User::create(['name' => 'Siswa Aktif', 'email' => 'siswa@test.com', 'password' => bcrypt('password'), 'role_id' => $studentRole->id, 'school_id' => $this->school->id]);
        $this->student = Student::create(['user_id' => $this->studentUser->id, 'nis' => '123', 'school_id' => $this->school->id]);

        StudentClass::create(['student_id' => $this->student->id, 'class_id' => $this->classroom->id, 'academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $teacherUser = User::create(['name' => 'Guru Aktif', 'email' => 'guru@test.com', 'password' => bcrypt('password'), 'role_id' => $teacherRole->id, 'school_id' => $this->school->id]);
        $this->teacher = Teacher::create(['user_id' => $teacherUser->id, 'nip' => '111', 'school_id' => $this->school->id]);
    }

    public function test_student_can_see_own_class_assignments()
    {
        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Matematika',
            'description' => 'Kerjakan halaman 10',
            'deadline' => now()->addDays(2),
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.assignments.index'));
        $response->assertStatus(200);
        $response->assertSee('Tugas Matematika');
        $response->assertSee('Kerjakan halaman 10');
    }

    public function test_student_cannot_see_other_class_assignments()
    {
        $otherClass = Classroom::create(['name' => 'Kelas XB', 'grade_level' => 10, 'school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id]);
        
        Assignment::create([
            'class_id' => $otherClass->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas XB',
            'description' => 'Rahasia',
            'deadline' => now()->addDays(2),
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.assignments.index'));
        $response->assertDontSee('Tugas XB');
    }

    public function test_cross_tenant_assignments_invisible()
    {
        $otherSchool = School::create(['name' => 'Sekolah Lain', 'is_active' => true]);
        $otherYear = AcademicYear::create(['year' => '2024/2025', 'school_id' => $otherSchool->id, 'is_active' => true]);
        $otherClass = Classroom::create(['name' => 'Kelas Rahasia', 'grade_level' => 10, 'school_id' => $otherSchool->id, 'academic_year_id' => $otherYear->id]);
        
        $otherTeacher = Teacher::create([
            'user_id' => User::create(['name' => 'Guru', 'email' => 'guru2@test.com', 'password' => bcrypt('123'), 'school_id' => $otherSchool->id, 'role_id' => Role::where('name', 'guru')->first()->id])->id,
            'school_id' => $otherSchool->id,
        ]);

        Assignment::create([
            'class_id' => $otherClass->id,
            'subject_id' => Subject::create(['name' => 'O', 'code' => 'O', 'school_id' => $otherSchool->id])->id,
            'teacher_id' => $otherTeacher->id,
            'title' => 'Tugas Tenant Lain',
            'description' => 'Rahasia',
            'deadline' => now()->addDays(2),
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.assignments.index'));
        $response->assertDontSee('Tugas Tenant Lain');
    }

    public function test_student_can_submit_assignment_and_file_is_stored_locally()
    {
        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 1',
            'description' => 'Desc',
            'deadline' => now()->addDays(1),
        ]);

        $file = UploadedFile::fake()->create('jawaban.pdf', 100);

        $response = $this->actingAs($this->studentUser)->post(route('siswa.assignments.submit', $assignment), [
            'file' => $file,
            'notes' => 'Ini jawaban saya'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)->first();
        $this->assertNotNull($submission);
        $this->assertEquals($this->student->id, $submission->student_id);
        $this->assertEquals('Ini jawaban saya', $submission->notes);
        
        // Assert stored in local disk, not public
        Storage::disk('local')->assertExists($submission->file_path);
        Storage::disk('public')->assertMissing($submission->file_path);
    }

    public function test_student_cannot_resubmit_assignment()
    {
        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 1',
            'description' => 'Desc',
            'deadline' => now()->addDays(1),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'file_path' => 'fake.pdf',
        ]);

        $file = UploadedFile::fake()->create('jawaban2.pdf', 100);

        $response = $this->actingAs($this->studentUser)->post(route('siswa.assignments.submit', $assignment), [
            'file' => $file,
        ]);

        $response->assertSessionHas('error', 'Anda sudah pernah mengumpulkan tugas ini.');
        $this->assertEquals(1, AssignmentSubmission::where('assignment_id', $assignment->id)->count());
    }

    public function test_student_cannot_submit_past_deadline()
    {
        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 1',
            'description' => 'Desc',
            'deadline' => now()->subDays(1),
        ]);

        $file = UploadedFile::fake()->create('jawaban.pdf', 100);

        $response = $this->actingAs($this->studentUser)->post(route('siswa.assignments.submit', $assignment), [
            'file' => $file,
        ]);

        $response->assertSessionHas('error', 'Batas waktu pengumpulan tugas sudah berakhir.');
        $this->assertEquals(0, AssignmentSubmission::count());
    }

    public function test_assignment_attachment_download_is_secure()
    {
        $file = UploadedFile::fake()->create('soal.pdf', 100);
        $path = $file->store('assignments/attachments', 'local');

        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Ada File',
            'description' => 'Desc',
            'deadline' => now()->addDays(1),
            'attachment_path' => $path,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.assignments.download', $assignment));
        $response->assertStatus(200);
        $response->assertDownload();

        // Coba user dari kelas lain download
        $otherClass = Classroom::create(['name' => 'Kelas XB', 'grade_level' => 10, 'school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id]);
        $otherStudent = Student::create([
            'user_id' => User::create(['name' => 'B', 'email' => 'b@test.com', 'password' => bcrypt('123'), 'school_id' => $this->school->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id, 
            'nis' => '444', 
            'school_id' => $this->school->id
        ]);
        StudentClass::create(['student_id' => $otherStudent->id, 'class_id' => $otherClass->id, 'academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $response2 = $this->actingAs($otherStudent->user)->get(route('siswa.assignments.download', $assignment));
        $response2->assertStatus(403);
    }

    public function test_student_submission_download_is_secure()
    {
        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas',
            'description' => 'Desc',
            'deadline' => now()->addDays(1),
        ]);

        $file = UploadedFile::fake()->create('jawaban.pdf', 100);
        $path = $file->store('assignments/submissions', 'local');

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'file_path' => $path,
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.assignments.submissions.download', $assignment));
        $response->assertStatus(200);
        $response->assertDownload();

        // Coba user lain dari kelas yg sama download
        $otherStudent = Student::create([
            'user_id' => User::create(['name' => 'C', 'email' => 'c@test.com', 'password' => bcrypt('123'), 'school_id' => $this->school->id, 'role_id' => Role::where('name', 'siswa')->first()->id])->id, 
            'nis' => '555', 
            'school_id' => $this->school->id
        ]);
        StudentClass::create(['student_id' => $otherStudent->id, 'class_id' => $this->classroom->id, 'academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        // Bakal kena 404 karena `where('student_id', $otherStudent->id)` will fail `firstOrFail()` 
        $response2 = $this->actingAs($otherStudent->user)->get(route('siswa.assignments.submissions.download', $assignment));
        $response2->assertStatus(404);
    }

    public function test_score_and_feedback_are_displayed()
    {
        $assignment = Assignment::create([
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Kuiz',
            'description' => 'Desc',
            'deadline' => now()->addDays(1),
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->student->id,
            'file_path' => 'file.pdf',
            'score' => 95,
            'feedback' => 'Sangat bagus, pertahankan!',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.assignments.show', $assignment));
        $response->assertSee('95');
        $response->assertSee('Sangat bagus, pertahankan!');
    }
}
