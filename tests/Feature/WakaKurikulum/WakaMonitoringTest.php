<?php

namespace Tests\Feature\WakaKurikulum;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\LearningMeeting;
use App\Models\Material;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Models\Waka;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WakaMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private User $wakaUser;
    private School $school;
    private Classroom $classroom;
    private Subject $subject;
    private Teacher $teacher;
    private AcademicYear $academicYear;
    private Semester $semesterGanjil;
    private Semester $semesterGenap;

    protected function setUp(): void
    {
        parent::setUp();

        $wakaRole = Role::firstOrCreate(['name' => 'waka', 'display_name' => 'Waka Kurikulum']);
        $guruRole = Role::firstOrCreate(['name' => 'guru', 'display_name' => 'Guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);

        $this->school = School::create(['name' => 'SMP SinergiEdu', 'is_active' => true]);

        $this->wakaUser = User::factory()->create([
            'role_id' => $wakaRole->id,
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);
        Waka::factory()->create(['user_id' => $this->wakaUser->id, 'school_id' => $this->school->id]);

        $teacherUser = User::factory()->create([
            'role_id' => $guruRole->id,
            'school_id' => $this->school->id,
            'name' => 'Andi Pratama',
            'is_active' => true,
        ]);
        $this->teacher = Teacher::create([
            'school_id' => $this->school->id,
            'user_id' => $teacherUser->id,
            'nip' => '198501012010011001',
        ]);

        $this->academicYear = AcademicYear::create([
            'school_id' => $this->school->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $this->semesterGanjil = Semester::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil',
            'is_active' => true,
        ]);

        $this->semesterGenap = Semester::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Genap',
            'is_active' => false,
        ]);

        $this->classroom = Classroom::create([
            'school_id' => $this->school->id,
            'name' => '7A',
            'grade_level' => '7',
            'education_level' => 'SMP',
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->subject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Matematika',
            'code' => 'MTK-7',
        ]);

        TeacherSubject::create([
            'school_id' => $this->school->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semesterGanjil->id,
        ]);
    }

    public function test_waka_can_access_monitoring_learning_page()
    {
        $response = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.learning'));
        $response->assertStatus(200);
        $response->assertSee('Monitoring Pembelajaran');
    }

    public function test_waka_filter_by_semester_does_not_throw_500()
    {
        $meetingGanjil = LearningMeeting::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semesterGanjil->id,
            'meeting_number' => 1,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Bilangan Bulat Pertemuan 1',
        ]);

        $materialGanjil = Material::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'learning_meeting_id' => $meetingGanjil->id,
            'title' => 'Materi Bilangan Bulat Ganjil',
        ]);

        $assignmentGanjil = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'learning_meeting_id' => $meetingGanjil->id,
            'title' => 'Tugas Bilangan Bulat Ganjil',
            'description' => 'Kerjakan soal 1-5',
            'deadline' => now()->addDays(3),
        ]);

        // Request with semester Ganjil
        $responseGanjil = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.learning', [
            'semester_id' => $this->semesterGanjil->id,
        ]));
        $responseGanjil->assertStatus(200);
        $responseGanjil->assertSee('Materi Bilangan Bulat Ganjil');
        $responseGanjil->assertSee('Tugas Bilangan Bulat Ganjil');

        // Request with semester Genap
        $responseGenap = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.learning', [
            'semester_id' => $this->semesterGenap->id,
        ]));
        $responseGenap->assertStatus(200);
        $responseGenap->assertDontSee('Materi Bilangan Bulat Ganjil');
        $responseGenap->assertDontSee('Tugas Bilangan Bulat Ganjil');
    }

    public function test_waka_filter_combination_works_seamlessly()
    {
        $response = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.learning', [
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semesterGanjil->id,
        ]));
        $response->assertStatus(200);
    }

    public function test_waka_can_preview_pdf_material_inline()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('materi-bilangan-bulat.pdf', 500, 'application/pdf');
        $path = $file->store('materials/pdfs', 'local');

        $material = Material::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Materi Bilangan Bulat',
            'file_path' => $path,
        ]);

        $response = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.materials.preview', $material));
        $response->assertStatus(200);
        $this->assertStringContainsString('inline', $response->headers->get('content-disposition') ?? '');
    }

    public function test_waka_can_download_material()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('materi-bilangan-bulat.pdf', 500, 'application/pdf');
        $path = $file->store('materials/pdfs', 'local');

        $material = Material::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Materi Bilangan Bulat',
            'file_path' => $path,
        ]);

        $response = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.materials.download', $material));
        $response->assertStatus(200);
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition') ?? '');
    }

    public function test_waka_preview_missing_material_returns_404()
    {
        $material = Material::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Materi Non-Existent File',
            'file_path' => 'materials/pdfs/missing-file.pdf',
        ]);

        $response = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.materials.preview', $material));
        $response->assertStatus(404);
    }

    public function test_waka_can_preview_and_download_assignment_and_submissions()
    {
        Storage::fake('local');
        $taskFile = UploadedFile::fake()->create('tugas-soal.pdf', 300, 'application/pdf');
        $taskPath = $taskFile->store('assignments/attachments', 'local');

        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas Matematika 1',
            'description' => 'Kerjakan soal UTS',
            'deadline' => now()->addDays(5),
            'attachment_path' => $taskPath,
        ]);

        // Preview assignment
        $previewResp = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.assignments.preview', $assignment));
        $previewResp->assertStatus(200);
        $this->assertStringContainsString('inline', $previewResp->headers->get('content-disposition') ?? '');

        // Download assignment
        $downloadResp = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.assignments.download', $assignment));
        $downloadResp->assertStatus(200);
        $this->assertStringContainsString('attachment', $downloadResp->headers->get('content-disposition') ?? '');

        // Create student & submission
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $studentUser = User::factory()->create([
            'role_id' => $siswaRole->id,
            'school_id' => $this->school->id,
            'name' => 'Ahmad Siswa',
        ]);
        $student = Student::create([
            'user_id' => $studentUser->id,
            'school_id' => $this->school->id,
            'nis' => '12345',
        ]);

        $subFile = UploadedFile::fake()->create('jawaban-ahmad.pdf', 200, 'application/pdf');
        $subPath = $subFile->store('assignments/submissions', 'local');

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_path' => $subPath,
            'submitted_at' => now(),
        ]);

        // Preview submission
        $subPreviewResp = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.assignments.submissions.preview', [
            'assignment' => $assignment,
            'submission' => $submission,
        ]));
        $subPreviewResp->assertStatus(200);
        $this->assertStringContainsString('inline', $subPreviewResp->headers->get('content-disposition') ?? '');

        // Download submission
        $subDownloadResp = $this->actingAs($this->wakaUser)->get(route('waka.monitoring.assignments.submissions.download', [
            'assignment' => $assignment,
            'submission' => $submission,
        ]));
        $subDownloadResp->assertStatus(200);
        $this->assertStringContainsString('attachment', $subDownloadResp->headers->get('content-disposition') ?? '');
    }
}
