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
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherAssignmentsTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;
    private User $guruA;
    private User $guruB;
    private AcademicYear $academicYear;
    private Semester $semester;
    private Classroom $classA;
    private Subject $subjectA;
    private Teacher $teacherProfileA;
    private Teacher $teacherProfileB;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);

        $this->schoolA = School::create(['name' => 'School A', 'npsn' => '111', 'email' => 'a@a.com', 'is_active' => true]);
        $this->schoolB = School::create(['name' => 'School B', 'npsn' => '222', 'email' => 'b@b.com', 'is_active' => true]);

        $this->academicYear = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => true]);
        $this->semester = Semester::create(['school_id' => $this->schoolA->id, 'academic_year_id' => $this->academicYear->id, 'name' => 'Ganjil', 'is_active' => true]);

        $this->guruA = User::create(['school_id' => $this->schoolA->id, 'name' => 'Guru A', 'email' => 'a@guru.com', 'password' => 'password', 'role_id' => Role::where('name', 'guru')->first()->id]);
        $this->guruB = User::create(['school_id' => $this->schoolB->id, 'name' => 'Guru B', 'email' => 'b@guru.com', 'password' => 'password', 'role_id' => Role::where('name', 'guru')->first()->id]);

        $this->teacherProfileA = Teacher::create(['school_id' => $this->schoolA->id, 'user_id' => $this->guruA->id, 'nip' => '1']);
        $this->teacherProfileB = Teacher::create(['school_id' => $this->schoolB->id, 'user_id' => $this->guruB->id, 'nip' => '2']);

        $this->classA = Classroom::create(['school_id' => $this->schoolA->id, 'name' => 'X', 'grade_level' => '10', 'education_level' => 'SMA']);
        $this->subjectA = Subject::create(['school_id' => $this->schoolA->id, 'name' => 'Matematika', 'code' => 'MAT']);

        TeacherSubject::create([
            'school_id' => $this->schoolA->id,
            'teacher_id' => $this->teacherProfileA->id,
            'subject_id' => $this->subjectA->id,
            'class_id' => $this->classA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        app(TenantService::class)->clear();
    }

    public function test_guru_can_view_own_assignments()
    {
        Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Matematika 1',
            'description' => 'Kerjakan LKS',
            'deadline' => now()->addDays(3),
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.assignments.index'));
        $response->assertStatus(200);
        $response->assertSee('Tugas Matematika 1');
    }

    public function test_guru_cannot_view_other_guru_assignments()
    {
        Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Guru A',
            'description' => 'Rahasia Guru A',
            'deadline' => now()->addDays(3),
        ]);

        $response = $this->actingAs($this->guruB)->get(route('guru.assignments.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Tugas Guru A');
    }

    public function test_guru_can_create_assignment_with_valid_context()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->guruA)->post(route('guru.assignments.store'), [
            'title' => 'Tugas Baru Valid',
            'description' => 'Kerjakan secepatnya',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'deadline' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'attachment' => $file,
        ]);

        $response->assertRedirect(route('guru.assignments.index'));
        
        $this->assertDatabaseHas('assignments', [
            'title' => 'Tugas Baru Valid',
            'teacher_id' => $this->teacherProfileA->id,
        ]);

        $assignment = Assignment::where('title', 'Tugas Baru Valid')->first();
        Storage::disk('local')->assertExists($assignment->attachment_path);
    }

    public function test_guru_cannot_create_assignment_with_invalid_context()
    {
        // Try creating with a classroom not assigned to the teacher
        $classB = Classroom::create(['school_id' => $this->schoolA->id, 'name' => 'XI', 'grade_level' => '11', 'education_level' => 'SMA']);
        
        $response = $this->actingAs($this->guruA)->post(route('guru.assignments.store'), [
            'title' => 'Tugas Ilegal',
            'description' => 'Kerjakan secepatnya',
            'class_id' => $classB->id,
            'subject_id' => $this->subjectA->id,
            'deadline' => now()->addDays(2)->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors('class_id');
        $this->assertDatabaseMissing('assignments', ['title' => 'Tugas Ilegal']);
    }

    public function test_guru_can_edit_own_assignment()
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Awal',
            'description' => 'Kerjakan LKS',
            'deadline' => now()->addDays(3),
        ]);

        $response = $this->actingAs($this->guruA)->put(route('guru.assignments.update', $assignment), [
            'title' => 'Tugas Direvisi',
            'description' => 'Kerjakan LKS',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'deadline' => now()->addDays(5)->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('guru.assignments.index'));
        $this->assertDatabaseHas('assignments', ['title' => 'Tugas Direvisi']);
    }

    public function test_guru_cannot_edit_other_guru_assignment()
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Awal',
            'description' => 'Kerjakan LKS',
            'deadline' => now()->addDays(3),
        ]);

        $response = $this->actingAs($this->guruB)->put(route('guru.assignments.update', $assignment), [
            'title' => 'Di-hack',
            'description' => 'Kerjakan',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'deadline' => now()->addDays(5)->format('Y-m-d\TH:i'),
        ]);

        $response->assertStatus(403);
    }

    public function test_guru_can_delete_assignment_without_submissions()
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Hapus',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(3),
        ]);

        $response = $this->actingAs($this->guruA)->delete(route('guru.assignments.destroy', $assignment));
        $response->assertRedirect(route('guru.assignments.index'));
        $this->assertDatabaseMissing('assignments', ['id' => $assignment->id]);
    }

    public function test_guru_cannot_delete_assignment_with_submissions()
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Ada Jawaban',
            'description' => 'Kerjakan',
            'deadline' => now()->addDays(3),
        ]);

        Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $studentUser = User::create(['school_id' => $this->schoolA->id, 'name' => 'Siswa 1', 'email' => 'siswa@s.com', 'password' => 'password', 'role_id' => Role::where('name', 'siswa')->first()->id]);
        $student = Student::create(['school_id' => $this->schoolA->id, 'user_id' => $studentUser->id, 'nisn' => '123']);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_path' => 'fake_path.pdf',
        ]);

        $response = $this->actingAs($this->guruA)->delete(route('guru.assignments.destroy', $assignment));
        $response->assertSessionHas('error', 'Tugas tidak dapat dihapus karena sudah ada siswa yang mengumpulkan jawaban.');
        
        $this->assertDatabaseHas('assignments', ['id' => $assignment->id]);
    }

    public function test_guru_can_download_own_assignment_attachment()
    {
        Storage::fake('local');
        $path = UploadedFile::fake()->create('soal.pdf')->store('assignments/attachments', 'local');

        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas A',
            'description' => 'Kerjakan LKS',
            'deadline' => now()->addDays(3),
            'attachment_path' => $path,
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.assignments.download', $assignment));
        $response->assertStatus(200);
    }

    public function test_guru_can_create_assignment_with_meeting_and_material()
    {
        $meeting = \App\Models\LearningMeeting::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 1,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Topik 1',
        ]);

        $material = \App\Models\Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => $meeting->id,
            'title' => 'Materi Pertemuan 1',
            'description' => 'Penjelasan',
        ]);

        $response = $this->actingAs($this->guruA)->post(route('guru.assignments.store'), [
            'title' => 'Tugas Terhubung Meeting & Material',
            'description' => 'Kerjakan',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => $meeting->id,
            'material_id' => $material->id,
            'deadline' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('guru.assignments.index'));

        $this->assertDatabaseHas('assignments', [
            'title' => 'Tugas Terhubung Meeting & Material',
            'learning_meeting_id' => $meeting->id,
            'material_id' => $material->id,
        ]);
    }

    public function test_guru_cannot_associate_invalid_meeting_or_material_context()
    {
        $classOther = Classroom::create(['school_id' => $this->schoolA->id, 'name' => 'XII', 'grade_level' => '12', 'education_level' => 'SMA']);
        $meetingOtherClass = \App\Models\LearningMeeting::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $classOther->id,
            'subject_id' => $this->subjectA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 2,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Topik 2',
        ]);

        // Attempt linking meeting with mismatched class
        $response = $this->actingAs($this->guruA)->post(route('guru.assignments.store'), [
            'title' => 'Tugas Mismatched Meeting',
            'description' => 'Kerjakan',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => $meetingOtherClass->id,
            'deadline' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors('learning_meeting_id');

        // Attempt linking other teacher's meeting
        $meetingGuruB = \App\Models\LearningMeeting::create([
            'teacher_id' => $this->teacherProfileB->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 3,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Topik Guru B',
        ]);

        $responseGuruB = $this->actingAs($this->guruA)->post(route('guru.assignments.store'), [
            'title' => 'Tugas Meeting Guru B',
            'description' => 'Kerjakan',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => $meetingGuruB->id,
            'deadline' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);

        $responseGuruB->assertSessionHasErrors('learning_meeting_id');
    }

    public function test_null_legacy_assignment_remains_editable()
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Tugas Legacy NULL',
            'description' => 'Kerjakan LKS',
            'deadline' => now()->addDays(3),
            'learning_meeting_id' => null,
            'material_id' => null,
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.assignments.edit', $assignment));
        $response->assertStatus(200);

        $responseUpdate = $this->actingAs($this->guruA)->put(route('guru.assignments.update', $assignment), [
            'title' => 'Tugas Legacy Updated Still NULL',
            'description' => 'Kerjakan LKS Updated',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => null,
            'material_id' => null,
            'deadline' => now()->addDays(4)->format('Y-m-d\TH:i'),
        ]);

        $responseUpdate->assertRedirect(route('guru.assignments.index'));
        $this->assertDatabaseHas('assignments', [
            'id' => $assignment->id,
            'title' => 'Tugas Legacy Updated Still NULL',
            'learning_meeting_id' => null,
            'material_id' => null,
        ]);
    }

    public function test_meeting_material_filtering_and_invalid_reset_validation()
    {
        $meetingA = \App\Models\LearningMeeting::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 1,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Pertemuan 1',
        ]);

        $meetingB = \App\Models\LearningMeeting::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 2,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Pertemuan 2',
        ]);

        $materialA = \App\Models\Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => $meetingA->id,
            'title' => 'Materi Pertemuan 1',
            'description' => 'Isi Pertemuan 1',
        ]);

        // Create assignment for meeting A with material A
        $responseCreate = $this->actingAs($this->guruA)->post(route('guru.assignments.store'), [
            'title' => 'Tugas Meeting 1',
            'description' => 'Kerjakan LKS',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => $meetingA->id,
            'material_id' => $materialA->id,
            'deadline' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);

        $responseCreate->assertRedirect(route('guru.assignments.index'));

        // Reject if materialA submitted under meetingB
        $responseInvalid = $this->actingAs($this->guruA)->post(route('guru.assignments.store'), [
            'title' => 'Tugas Invalid Combination',
            'description' => 'Mismatched meeting & material',
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'learning_meeting_id' => $meetingB->id,
            'material_id' => $materialA->id,
            'deadline' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ]);

        $responseInvalid->assertSessionHasErrors('material_id');
    }
}
