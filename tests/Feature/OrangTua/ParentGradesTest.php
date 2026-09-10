<?php

namespace Tests\Feature\OrangTua;

use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentGrade;
use App\Models\StudentParent;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentGradesTest extends TestCase
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

    public function test_parent_can_view_grades_index_of_own_child(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id, 'name' => 'Anak Kandung']);
        $child = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $childUser->id,
            'parent_id' => $this->parentProfile->id,
            'nisn' => '111',
        ]);

        $classroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 10 A', 'grade_level' => 10]);
        $child->classes()->attach($classroom->id, ['academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        StudentGrade::create([
            'school_id' => $this->school->id,
            'student_id' => $child->id,
            'class_id' => $classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'average_score' => 85,
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.grades.index'));
        $response->assertStatus(200);
        $response->assertSee('Anak Kandung');
        $response->assertSee('Matematika');
        $response->assertSee('85');
    }

    public function test_parent_cannot_view_grades_detail_of_unrelated_child(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $otherClassroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 10 X', 'grade_level' => 10]);
        $otherChild->classes()->attach($otherClassroom->id, ['academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $otherGrade = StudentGrade::create([
            'school_id' => $this->school->id,
            'student_id' => $otherChild->id,
            'class_id' => $otherClassroom->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'average_score' => 90,
        ]);

        // Attempting to visit direct URL should return 403
        $response = $this->actingAs($this->parentUser)->get(route('orangtua.grades.show', $otherGrade->id));
        $response->assertStatus(403);
    }

    public function test_invalid_child_id_on_index_falls_back_safely(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        
        // Parent's own child
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id, 'name' => 'Valid Child']);
        $child = Student::create(['school_id' => $this->school->id, 'user_id' => $childUser->id, 'parent_id' => $this->parentProfile->id]);
        
        // Unrelated child
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id, 'name' => 'Hacker Target']);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        // Trying to access index with other child's ID
        $response = $this->actingAs($this->parentUser)->get(route('orangtua.grades.index', ['student_id' => $otherChild->id]));
        $response->assertStatus(200);
        $response->assertSee('Valid Child');
        $response->assertDontSee('Hacker Target');
    }

    public function test_grade_detail_shows_correct_metrics_and_feedback(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id, 'name' => 'Test Student']);
        $child = Student::create(['school_id' => $this->school->id, 'user_id' => $childUser->id, 'parent_id' => $this->parentProfile->id]);

        $classroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 10 B', 'grade_level' => 10]);
        $child->classes()->attach($classroom->id, ['academic_year_id' => $this->academicYear->id, 'school_id' => $this->school->id]);

        $grade = StudentGrade::create([
            'school_id' => $this->school->id,
            'student_id' => $child->id,
            'class_id' => $classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'average_score' => 88,
        ]);

        $assignment1 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Matematika 1',
            'description' => 'Desc',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);
        AssignmentSubmission::create([
            'assignment_id' => $assignment1->id,
            'student_id' => $child->id,
            'file_path' => 'file.pdf',
            'score' => 95,
            'feedback' => 'Good job on Math 1!',
            'submitted_at' => now(),
        ]);

        $assignment2 = Assignment::create([
            'school_id' => $this->school->id,
            'class_id' => $classroom->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Tugas Matematika 2',
            'description' => 'Desc',
            'type' => 'homework',
            'deadline' => now()->addDays(2),
        ]);
        // Unscored submission
        AssignmentSubmission::create([
            'assignment_id' => $assignment2->id,
            'student_id' => $child->id,
            'file_path' => 'file2.pdf',
            'score' => null,
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.grades.show', $grade->id));
        
        $response->assertStatus(200);
        $response->assertSee('Tugas Matematika 1');
        $response->assertSee('Good job on Math 1!'); // Feedback seen
        $response->assertSee('95');
        
        $response->assertSee('Tugas Matematika 2');
        $response->assertSee('Menunggu Penilaian');
        
        // Assert summary "1 / 2" for Graded Tasks in the view
        $response->assertSee('1 / 2');
    }
}
