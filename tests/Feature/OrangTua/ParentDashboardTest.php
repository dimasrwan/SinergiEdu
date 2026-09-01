<?php

namespace Tests\Feature\OrangTua;

use App\Models\AcademicYear;
use App\Models\Assignment;
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

class ParentDashboardTest extends TestCase
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

    public function test_parent_can_view_dashboard_with_owned_child_data(): void
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

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.dashboard'));

        $response->assertStatus(200);
        $response->assertSee($childUser->name);
        $response->assertSee('Kelas 10 A');
        $response->assertSee('NISN: 111');
    }

    public function test_parent_with_multiple_children_can_switch_child(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $child1User = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $child1 = Student::create(['school_id' => $this->school->id, 'user_id' => $child1User->id, 'parent_id' => $this->parentProfile->id]);
        
        $child2User = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $child2 = Student::create(['school_id' => $this->school->id, 'user_id' => $child2User->id, 'parent_id' => $this->parentProfile->id]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.dashboard'));
        $response->assertSee($child1User->name);
        $response->assertSee('Pilih Anak:');

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.dashboard', ['student_id' => $child2->id]));
        $response->assertSee($child2User->name);
    }

    public function test_parent_cannot_view_dashboard_of_unrelated_student(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $ownChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $ownChild = Student::create(['school_id' => $this->school->id, 'user_id' => $ownChildUser->id, 'parent_id' => $this->parentProfile->id]);
        
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.dashboard', ['student_id' => $otherChild->id]));
        
        $response->assertSee($ownChildUser->name);
        $response->assertDontSee($otherChildUser->name);
    }

    public function test_parent_cannot_view_student_from_another_school(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $ownChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        Student::create(['school_id' => $this->school->id, 'user_id' => $ownChildUser->id, 'parent_id' => $this->parentProfile->id]);

        $otherSchoolChildUser = User::factory()->create(['school_id' => $this->otherSchool->id, 'role_id' => $siswaRole->id]);
        $otherSchoolChild = Student::create(['school_id' => $this->otherSchool->id, 'user_id' => $otherSchoolChildUser->id, 'parent_id' => null]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.dashboard', ['student_id' => $otherSchoolChild->id]));
        
        $response->assertDontSee($otherSchoolChildUser->name);
    }

    public function test_dashboard_shows_active_classroom_assignments_and_grades(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $child = Student::create(['school_id' => $this->school->id, 'user_id' => $childUser->id, 'parent_id' => $this->parentProfile->id]);

        $classroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 10 A', 'grade_level' => 10]);
        $child->classes()->attach($classroom->id, ['academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $assignment = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Aljabar Parent Test',
            'description' => 'Deskripsi tugas',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);

        StudentGrade::create([
            'school_id' => $this->school->id,
            'student_id' => $child->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'assignment_score' => 88.5,
        ]);

        Feedback::create([
            'school_id' => $this->school->id,
            'student_id' => $child->id,
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
            'title' => 'Kerajinan',
            'message' => 'Sangat rajin!',
            'type' => 'positive',
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.dashboard'));
        
        $response->assertSee('Tugas Aljabar Parent Test');
        $response->assertSee('88.5');
        $response->assertSee('Sangat rajin!');
    }

    public function test_dashboard_shows_empty_state_correctly(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        Student::create(['school_id' => $this->school->id, 'user_id' => $childUser->id, 'parent_id' => $this->parentProfile->id]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.dashboard'));
        
        $response->assertSee('Belum Ada Kelas Aktif');
    }
}
