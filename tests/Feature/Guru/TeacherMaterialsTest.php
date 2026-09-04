<?php

declare(strict_types=1);

namespace Tests\Feature\Guru;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherMaterialsTest extends TestCase
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

    public function test_guru_can_view_own_materials()
    {
        Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Materi A',
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.materials.index'));
        $response->assertStatus(200);
        $response->assertSee('Materi A');
    }

    public function test_guru_cannot_view_other_guru_materials()
    {
        Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Materi Milik A',
        ]);

        $response = $this->actingAs($this->guruB)->get(route('guru.materials.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Materi Milik A');
    }

    public function test_guru_can_create_material()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->guruA)->post(route('guru.materials.store'), [
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Materi Baru',
            'file' => $file,
        ]);

        $response->assertRedirect(route('guru.materials.index'));
        
        $this->assertDatabaseHas('materials', [
            'title' => 'Materi Baru',
            'teacher_id' => $this->teacherProfileA->id,
        ]);

        $material = Material::where('title', 'Materi Baru')->first();
        Storage::disk('local')->assertExists($material->file_path);
    }

    public function test_guru_cannot_edit_other_guru_material()
    {
        $materialA = Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Materi A',
        ]);

        $response = $this->actingAs($this->guruB)->get(route('guru.materials.edit', $materialA));
        $response->assertStatus(403);
    }

    public function test_guru_can_download_own_material()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');
        $path = $file->store('materials/pdfs', 'local');

        $materialA = Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Materi A',
            'file_path' => $path,
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.materials.download', $materialA));
        $response->assertStatus(200);
    }

    public function test_guru_cannot_download_other_guru_material()
    {
        Storage::fake('local');
        $path = UploadedFile::fake()->create('document.pdf')->store('materials/pdfs', 'local');

        $materialA = Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Materi A',
            'file_path' => $path,
        ]);

        $response = $this->actingAs($this->guruB)->get(route('guru.materials.download', $materialA));
        $response->assertStatus(403);
    }

    public function test_guru_can_delete_own_material_and_file_is_removed()
    {
        Storage::fake('local');
        $path = UploadedFile::fake()->create('document.pdf')->store('materials/pdfs', 'local');

        $materialA = Material::create([
            'teacher_id' => $this->teacherProfileA->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'title' => 'Materi A',
            'file_path' => $path,
        ]);

        Storage::disk('local')->assertExists($path);

        $response = $this->actingAs($this->guruA)->delete(route('guru.materials.destroy', $materialA));
        $response->assertRedirect(route('guru.materials.index'));

        $this->assertDatabaseMissing('materials', ['id' => $materialA->id]);
        Storage::disk('local')->assertMissing($path);
    }
}
