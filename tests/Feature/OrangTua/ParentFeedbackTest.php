<?php

namespace Tests\Feature\OrangTua;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentFeedbackTest extends TestCase
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
        $teacherUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $guruRole->id, 'name' => 'Guru Budi']);
        $this->teacher = Teacher::create(['school_id' => $this->school->id, 'user_id' => $teacherUser->id]);
        $this->subject = Subject::create(['school_id' => $this->school->id, 'name' => 'Matematika', 'code' => 'MTK']);
    }

    public function test_parent_can_view_feedbacks_of_own_child(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id, 'name' => 'Anak Kandung']);
        $child = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $childUser->id,
            'parent_id' => $this->parentProfile->id,
            'nisn' => '111',
        ]);

        Feedback::create([
            'teacher_id' => $this->teacher->id,
            'student_id' => $child->id,
            'subject_id' => $this->subject->id,
            'title' => 'Perkembangan Sangat Baik',
            'message' => 'Anak ini sangat aktif',
            'type' => 'positive',
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.feedbacks.index'));
        $response->assertStatus(200);
        $response->assertSee('Anak Kandung');
        $response->assertSee('Perkembangan Sangat Baik');
        $response->assertSee('Anak ini sangat aktif');
        $response->assertSee('Positif');
    }

    public function test_parent_cannot_view_feedback_detail_of_unrelated_child(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $otherFeedback = Feedback::create([
            'teacher_id' => $this->teacher->id,
            'student_id' => $otherChild->id,
            'title' => 'Catatan Rahasia',
            'message' => 'Hanya untuk parent lain',
            'type' => 'neutral',
        ]);

        // Access via index should safely fallback/empty state
        $response1 = $this->actingAs($this->parentUser)->get(route('orangtua.feedbacks.index', ['student_id' => $otherChild->id]));
        $response1->assertStatus(200);
        $response1->assertDontSee('Catatan Rahasia');

        // Direct detail access should 403
        $response2 = $this->actingAs($this->parentUser)->get(route('orangtua.feedbacks.show', $otherFeedback->id));
        $response2->assertStatus(403);
    }
    
    public function test_search_feedback(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $childUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id, 'name' => 'Anak Saya']);
        $child = Student::create(['school_id' => $this->school->id, 'user_id' => $childUser->id, 'parent_id' => $this->parentProfile->id]);

        Feedback::create([
            'teacher_id' => $this->teacher->id,
            'student_id' => $child->id,
            'subject_id' => $this->subject->id,
            'title' => 'Juara Satu',
            'message' => 'Luar biasa',
            'type' => 'positive',
        ]);
        
        Feedback::create([
            'teacher_id' => $this->teacher->id,
            'student_id' => $child->id,
            'title' => 'Lainnya',
            'message' => 'Luar biasa x',
            'type' => 'neutral',
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.feedbacks.index', ['search' => 'Juara']));
        $response->assertStatus(200);
        $response->assertSee('Juara Satu');
        $response->assertDontSee('Lainnya');
        
        $responseTeacher = $this->actingAs($this->parentUser)->get(route('orangtua.feedbacks.index', ['search' => 'Budi']));
        $responseTeacher->assertStatus(200);
        $responseTeacher->assertSee('Juara Satu');
        $responseTeacher->assertSee('Lainnya'); // Because both created by Guru Budi
    }
}
