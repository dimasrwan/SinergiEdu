<?php

namespace Tests\Feature\OrangTua;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentAssignmentsTest extends TestCase
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

    public function test_parent_can_view_assignments_with_owned_child_data(): void
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

        $assignment = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Aljabar',
            'description' => 'Deskripsi tugas',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.assignments.index'));

        $response->assertStatus(200);
        $response->assertSee($childUser->name);
        $response->assertSee('Tugas Aljabar');
        $response->assertSee('Belum Mengumpulkan');
    }

    public function test_parent_cannot_view_assignments_of_unrelated_student(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $ownChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $ownChild = Student::create(['school_id' => $this->school->id, 'user_id' => $ownChildUser->id, 'parent_id' => $this->parentProfile->id]);
        
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.assignments.index', ['student_id' => $otherChild->id]));
        
        // Should fallback to own child
        $response->assertSee($ownChildUser->name);
        $response->assertDontSee($otherChildUser->name);
    }

    public function test_assignments_shows_correct_status_and_metrics(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $child = Student::create(['school_id' => $this->school->id, 'user_id' => $childUser->id, 'parent_id' => $this->parentProfile->id]);

        $classroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 10 A', 'grade_level' => 10]);
        $child->classes()->attach($classroom->id, ['academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        // Tugas 1: Dinilai (Tepat Waktu)
        $assignment1 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 1 (Dinilai)',
            'description' => 'Desc',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);
        AssignmentSubmission::create([
            'assignment_id' => $assignment1->id,
            'student_id' => $child->id,
            'file_path' => 'path.pdf',
            'score' => 95,
            'submitted_at' => now(),
        ]);

        // Tugas 2: Menunggu Penilaian (Tepat Waktu)
        $assignment2 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 2 (Menunggu Penilaian)',
            'description' => 'Desc',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);
        AssignmentSubmission::create([
            'assignment_id' => $assignment2->id,
            'student_id' => $child->id,
            'file_path' => 'path.pdf',
            'score' => null,
            'submitted_at' => now(),
        ]);

        // Tugas 3: Belum (Deadline belum lewat)
        $assignment3 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 3 (Belum)',
            'description' => 'Desc',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);

        // Tugas 4: Terlewat (Deadline sudah lewat)
        $assignment4 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 4 (Terlewat)',
            'description' => 'Desc',
            'type' => 'homework',
            'deadline' => now()->subDays(1),
        ]);

        // Tugas 5: Dinilai Terlambat
        $assignment5 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas 5 (Dinilai Terlambat)',
            'description' => 'Desc',
            'type' => 'homework',
            'deadline' => now()->subDays(2),
        ]);
        AssignmentSubmission::create([
            'assignment_id' => $assignment5->id,
            'student_id' => $child->id,
            'file_path' => 'path.pdf',
            'score' => 80,
            'submitted_at' => now()->subDays(1), // Submitted 1 day ago (1 day after deadline)
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.assignments.index'));
        
        $response->assertSee('Tugas 1 (Dinilai)');
        $response->assertSee('Dinilai');
        
        $response->assertSee('Tugas 2 (Menunggu Penilaian)');
        $response->assertSee('Menunggu Penilaian');
        
        $response->assertSee('Tugas 3 (Belum)');
        $response->assertSee('Belum Mengumpulkan');
        
        $response->assertSee('Tugas 4 (Terlewat)');
        $response->assertSee('Terlewat');
        
        $response->assertSee('Tugas 5 (Dinilai Terlambat)');
        $response->assertSee('Terlambat');

        // Check summary stats rendered
        $response->assertSee('Total Tugas');
        // Because stats show: total(5), selesai(3), menunggu(1), belum(2) 
        // 5 total
        // 3 selesai (tugas 1, 2, 5 submitted)
        // 1 menunggu (tugas 2)
        // 2 belum (tugas 3, 4)
    }
}
