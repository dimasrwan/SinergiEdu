<?php

namespace Tests\Feature\OrangTua;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Feedback;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentParent;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentProgressTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private School $otherSchool;
    private User $parentUser;
    private StudentParent $parentProfile;
    private AcademicYear $academicYear;
    private Semester $semester;
    private Subject $subject;
    private Teacher $teacher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Sekolah Test',
            'npsn' => '12345678',
            'address' => 'Jl. Test 1',
            'status' => 'active',
        ]);
        
        $this->otherSchool = School::create([
            'name' => 'Sekolah Lain',
            'npsn' => '87654321',
            'address' => 'Jl. Test 2',
            'status' => 'active',
        ]);

        $this->academicYear = AcademicYear::create([
            'school_id' => $this->school->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $this->semester = Semester::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil',
            'is_active' => true,
        ]);

        $parentRole = Role::firstOrCreate(['name' => 'orangtua', 'display_name' => 'Orang Tua']);
        $this->parentUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => $parentRole->id,
        ]);

        $this->parentProfile = StudentParent::create([
            'school_id' => $this->school->id,
            'user_id' => $this->parentUser->id,
            'phone' => '081234567890',
        ]);

        $guruRole = Role::firstOrCreate(['name' => 'guru', 'display_name' => 'Guru']);
        $teacherUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $guruRole->id]);
        $this->teacher = Teacher::create(['school_id' => $this->school->id, 'user_id' => $teacherUser->id]);
        $this->subject = Subject::create(['school_id' => $this->school->id, 'name' => 'Matematika', 'code' => 'MTK']);
    }

    public function test_parent_can_view_progress_with_owned_child_data(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $child = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $childUser->id,
            'parent_id' => $this->parentProfile->id,
            'nisn' => '111',
        ]);

        $classroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 10 A', 'grade_level' => 10]);
        $child->classes()->attach($classroom->id, ['academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.progress.index'));

        $response->assertStatus(200);
        $response->assertSee($childUser->name);
    }

    public function test_parent_cannot_view_progress_of_unrelated_student(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $ownChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $ownChild = Student::create(['school_id' => $this->school->id, 'user_id' => $ownChildUser->id, 'parent_id' => $this->parentProfile->id]);
        
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.progress.index', ['student_id' => $otherChild->id]));
        
        // Should fallback to own child
        $response->assertSee($ownChildUser->name);
        $response->assertDontSee($otherChildUser->name);
    }

    public function test_progress_shows_active_classroom_assignments_and_grades(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $child = Student::create(['school_id' => $this->school->id, 'user_id' => $childUser->id, 'parent_id' => $this->parentProfile->id]);

        $classroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 10 A', 'grade_level' => 10]);
        $child->classes()->attach($classroom->id, ['academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $assignment1 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 1',
            'description' => 'Deskripsi tugas 1',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);

        $assignment2 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 2',
            'description' => 'Deskripsi tugas 2',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);

        // Submit one task
        AssignmentSubmission::create([
            'assignment_id' => $assignment1->id,
            'student_id' => $child->id,
            'file_path' => 'path/to/file.pdf',
        ]);

        StudentGrade::create([
            'school_id' => $this->school->id,
            'student_id' => $child->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'pre_test_score' => 80,
            'assignment_score' => 88.5,
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.progress.index'));
        
        $response->assertSee('Matematika');
        $response->assertSee('1 / 2 Selesai');
        $response->assertSee('88.5');
    }

    public function test_parent_can_see_meeting_and_material_context_on_assignments()
    {
        $classroom = Classroom::create([
            'school_id' => $this->school->id,
            'name' => 'Kelas 8A',
            'grade_level' => 8,
            'education_level' => 'SMP',
        ]);

        $siswaRole = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $childUser = User::create([
            'school_id' => $this->school->id,
            'name' => 'Anak Test 2',
            'email' => 'anak2@test.com',
            'password' => bcrypt('password'),
            'role_id' => $siswaRole->id,
        ]);

        $child = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $childUser->id,
            'parent_id' => $this->parentProfile->id,
            'nisn' => '1234567891',
        ]);
        $child->classes()->attach($classroom->id, [
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $meeting = \App\Models\LearningMeeting::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 4,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Aljabar',
        ]);

        $material = \App\Models\Material::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'learning_meeting_id' => $meeting->id,
            'title' => 'Materi Aljabar Dasar',
            'description' => 'Penjelasan',
        ]);

        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'title' => 'Tugas Aljabar 1',
            'description' => 'Kerjakan Latihan 1',
            'deadline' => now()->addDays(3),
            'learning_meeting_id' => $meeting->id,
            'material_id' => $material->id,
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.assignments.index', ['student_id' => $child->id]));
        $response->assertStatus(200);
        $response->assertSee('Pertemuan 4');
        $response->assertSee('Materi Aljabar Dasar');
    }
}
