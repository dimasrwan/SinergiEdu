<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentStudentLinkingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Bypass policy checks to focus on tenant logic
        \Illuminate\Support\Facades\Gate::before(function () {
            return true;
        });
        
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);
        Role::firstOrCreate(['name' => 'orangtua'], ['display_name' => 'Orang Tua']);
        Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
    }

    public function test_parent_can_link_to_multiple_students_within_same_school()
    {
        $this->withoutExceptionHandling();
        $school = School::create(['name' => 'School A']);
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $school->id]);

        $user1 = User::create(['name' => 'S1', 'email' => 's1@a.com', 'password' => 'pwd', 'role_id' => Role::where('name','siswa')->first()->id, 'school_id' => $school->id]);
        $user2 = User::create(['name' => 'S2', 'email' => 's2@a.com', 'password' => 'pwd', 'role_id' => Role::where('name','siswa')->first()->id, 'school_id' => $school->id]);
        $student1 = Student::create(['user_id' => $user1->id, 'nis' => '111', 'gender' => 'L', 'date_of_birth' => '2010-01-01', 'school_id' => $school->id]);
        $student2 = Student::create(['user_id' => $user2->id, 'nis' => '222', 'gender' => 'P', 'date_of_birth' => '2010-01-02', 'school_id' => $school->id]);

        $response = $this->actingAs($admin)->post(route('admin.parents.store'), [
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => 'password',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'students' => [$student1->id, $student2->id],
        ]);

        $response->assertRedirect(route('admin.parents.index'));
        
        $parent = StudentParent::whereHas('user', function ($q) {
            $q->where('email', 'parent@test.com');
        })->first();

        $this->assertNotNull($parent);
        
        // Assert di DB
        $this->assertEquals($parent->id, $student1->fresh()->parent_id);
        $this->assertEquals($parent->id, $student2->fresh()->parent_id);
    }

    public function test_parent_cannot_link_to_student_from_another_school()
    {
        $this->withoutExceptionHandling();
        $school1 = School::create(['name' => 'School 1']);
        $school2 = School::create(['name' => 'School 2']); // Tenant lain
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $school1->id]);

        $userOther = User::create(['name' => 'S3', 'email' => 's3@b.com', 'password' => 'pwd', 'role_id' => Role::where('name','siswa')->first()->id, 'school_id' => $school2->id]);
        $studentFromOtherSchool = Student::create(['user_id' => $userOther->id, 'nis' => '333', 'gender' => 'L', 'date_of_birth' => '2010-01-01', 'school_id' => $school2->id]);

        $response = $this->actingAs($admin)->post(route('admin.parents.store'), [
            'name' => 'Test Parent 2',
            'email' => 'parent2@test.com',
            'password' => 'password',
            'phone' => '08123456789',
            'address' => 'Test Address',
            // Mencoba menghubungkan dengan siswa tenant lain
            'students' => [$studentFromOtherSchool->id],
        ]);

        $response->assertRedirect(route('admin.parents.index'));
        
        // Pastikan student TIDAK terhubung
        $this->assertNull($studentFromOtherSchool->fresh()->parent_id);
    }

    public function test_student_can_link_to_parent_within_same_school()
    {
        $this->withoutExceptionHandling();
        $school = School::create(['name' => 'School A']);
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $school->id]);

        $userParent = User::create(['name' => 'P1', 'email' => 'p1@a.com', 'password' => 'pwd', 'role_id' => Role::where('name','orangtua')->first()->id, 'school_id' => $school->id]);
        $parent = StudentParent::create(['user_id' => $userParent->id, 'school_id' => $school->id, 'phone' => '123', 'address' => 'Test']);

        $response = $this->actingAs($admin)->post(route('admin.students.store'), [
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => 'password',
            'nis' => '123456',
            'gender' => 'L',
            'date_of_birth' => '2010-01-01',
            'parent_id' => $parent->id,
        ]);

        $response->assertRedirect(route('admin.students.index'));
        
        $student = Student::where('nis', '123456')->first();
        $this->assertEquals($parent->id, $student->parent_id);
    }

    public function test_student_cannot_link_to_parent_from_another_school()
    {
        $this->withoutExceptionHandling();
        $school1 = School::create(['name' => 'School 1']);
        $school2 = School::create(['name' => 'School 2']);
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $school1->id]);

        $userParentOther = User::create(['name' => 'P2', 'email' => 'p2@b.com', 'password' => 'pwd', 'role_id' => Role::where('name','orangtua')->first()->id, 'school_id' => $school2->id]);
        $parentFromOtherSchool = StudentParent::create(['user_id' => $userParentOther->id, 'school_id' => $school2->id, 'phone' => '123', 'address' => 'Test']);

        $response = $this->actingAs($admin)->post(route('admin.students.store'), [
            'name' => 'Test Student 2',
            'email' => 'student2@test.com',
            'password' => 'password',
            'nis' => '123457',
            'gender' => 'L',
            'date_of_birth' => '2010-01-01',
            'parent_id' => $parentFromOtherSchool->id,
        ]);

        $response->assertRedirect(route('admin.students.index'));
        
        // Relasi ditolak karena parent berasal dari school lain (hasil validasi tenantController)
        $student = Student::where('nis', '123457')->first();
        $this->assertNull($student->parent_id);
    }
}
