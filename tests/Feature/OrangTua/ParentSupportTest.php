<?php

namespace Tests\Feature\OrangTua;

use App\Models\AcademicYear;
use App\Models\ParentSupport;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentSupportTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private School $otherSchool;
    private User $parentUser;
    private StudentParent $parentProfile;
    private AcademicYear $academicYear;
    private Semester $semester;
    private Role $siswaRole;
    private Student $childA;
    private Student $childB;

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
        $this->siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);

        $this->parentUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => $parentRole->id,
        ]);

        $this->parentProfile = StudentParent::create([
            'school_id' => $this->school->id,
            'user_id' => $this->parentUser->id,
            'phone' => '081234567890',
        ]);

        $childUserA = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $this->siswaRole->id, 'name' => 'Child A']);
        $this->childA = Student::create(['school_id' => $this->school->id, 'user_id' => $childUserA->id, 'parent_id' => $this->parentProfile->id, 'nisn' => '111']);

        $childUserB = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $this->siswaRole->id, 'name' => 'Child B']);
        $this->childB = Student::create(['school_id' => $this->school->id, 'user_id' => $childUserB->id, 'parent_id' => $this->parentProfile->id, 'nisn' => '222']);
    }

    public function test_parent_can_view_support_page(): void
    {
        $response = $this->actingAs($this->parentUser)->get(route('orangtua.support.index'));
        $response->assertStatus(200);
        $response->assertSee('Dukungan Belajar');
        $response->assertSee('Child A');
    }

    public function test_parent_can_create_support_for_own_child(): void
    {
        $response = $this->actingAs($this->parentUser)->post(route('orangtua.support.store'), [
            'student_id' => $this->childA->id,
            'week_number' => 'Minggu 1',
            'support_description' => 'Mendampingi belajar matematika',
            'general_feedback' => 'Anak sudah mulai paham',
            'action_plan' => 'Lanjutkan latihan soal',
        ]);

        $response->assertRedirect(route('orangtua.support.index', ['student_id' => $this->childA->id]));
        $this->assertDatabaseHas('parent_supports', [
            'school_id' => $this->school->id,
            'student_id' => $this->childA->id,
            'week_number' => 'Minggu 1',
            'support_description' => 'Mendampingi belajar matematika',
        ]);
    }

    public function test_parent_can_switch_child(): void
    {
        ParentSupport::create([
            'school_id' => $this->school->id,
            'student_id' => $this->childA->id,
            'week_number' => 'Minggu A',
            'support_description' => 'Support untuk A',
        ]);
        ParentSupport::create([
            'school_id' => $this->school->id,
            'student_id' => $this->childB->id,
            'week_number' => 'Minggu B',
            'support_description' => 'Support untuk B',
        ]);

        $responseA = $this->actingAs($this->parentUser)->get(route('orangtua.support.index', ['student_id' => $this->childA->id]));
        $responseA->assertSee('Support untuk A');
        $responseA->assertDontSee('Support untuk B');

        $responseB = $this->actingAs($this->parentUser)->get(route('orangtua.support.index', ['student_id' => $this->childB->id]));
        $responseB->assertSee('Support untuk B');
        $responseB->assertDontSee('Support untuk A');
    }

    public function test_invalid_child_falls_back_safely(): void
    {
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $this->siswaRole->id]);
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.support.index', ['student_id' => $otherChild->id]));
        // Should fallback to childA (first child of parentUser)
        $response->assertStatus(200);
        $response->assertSee('Child A');
    }

    public function test_parent_cannot_create_support_for_unrelated_child(): void
    {
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $this->siswaRole->id]);
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $response = $this->actingAs($this->parentUser)->post(route('orangtua.support.store'), [
            'student_id' => $otherChild->id,
            'week_number' => 'Minggu 1',
            'support_description' => 'Support ilegal',
        ]);

        $response->assertSessionHasErrors('student_id');
        $this->assertDatabaseMissing('parent_supports', [
            'support_description' => 'Support ilegal',
        ]);
    }

    public function test_parent_cannot_create_support_for_cross_tenant_child(): void
    {
        $otherChildUser = User::factory()->create(['school_id' => $this->otherSchool->id, 'role_id' => $this->siswaRole->id]);
        $otherParent = StudentParent::create(['school_id' => $this->otherSchool->id, 'user_id' => User::factory()->create(['school_id' => $this->otherSchool->id])->id]);
        $otherChild = Student::create(['school_id' => $this->otherSchool->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        $response = $this->actingAs($this->parentUser)->post(route('orangtua.support.store'), [
            'student_id' => $otherChild->id,
            'week_number' => 'Minggu 1',
            'support_description' => 'Cross tenant attack',
        ]);

        $response->assertSessionHasErrors('student_id');
        $this->assertDatabaseMissing('parent_supports', [
            'support_description' => 'Cross tenant attack',
        ]);
    }

    public function test_parent_only_sees_support_of_authorized_children(): void
    {
        $otherChildUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $this->siswaRole->id]);
        $otherParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => User::factory()->create(['school_id' => $this->school->id])->id]);
        $otherChild = Student::create(['school_id' => $this->school->id, 'user_id' => $otherChildUser->id, 'parent_id' => $otherParent->id]);

        ParentSupport::create([
            'school_id' => $this->school->id,
            'student_id' => $otherChild->id,
            'week_number' => 'Rahasia',
            'support_description' => 'Support Anak Lain',
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.support.index'));
        $response->assertDontSee('Support Anak Lain');
    }

    public function test_school_id_is_forced_to_current_tenant(): void
    {
        $response = $this->actingAs($this->parentUser)->post(route('orangtua.support.store'), [
            'school_id' => $this->otherSchool->id, // Try to manipulate
            'student_id' => $this->childA->id,
            'week_number' => 'Minggu X',
            'support_description' => 'Test Tenant Override',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('parent_supports', [
            'student_id' => $this->childA->id,
            'school_id' => $this->school->id, // Forced to parent's school
            'support_description' => 'Test Tenant Override',
        ]);
        
        $this->assertDatabaseMissing('parent_supports', [
            'school_id' => $this->otherSchool->id,
            'support_description' => 'Test Tenant Override',
        ]);
    }

    public function test_academic_context_saved_correctly(): void
    {
        $response = $this->actingAs($this->parentUser)->post(route('orangtua.support.store'), [
            'student_id' => $this->childA->id,
            'week_number' => 'Minggu Y',
            'support_description' => 'Test Academic Context',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('parent_supports', [
            'student_id' => $this->childA->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);
    }

    public function test_validation_works(): void
    {
        $response = $this->actingAs($this->parentUser)->post(route('orangtua.support.store'), [
            'student_id' => $this->childA->id,
            // missing week_number
            // missing support_description
        ]);

        $response->assertSessionHasErrors(['week_number', 'support_description']);
    }

    public function test_empty_state_works(): void
    {
        $response = $this->actingAs($this->parentUser)->get(route('orangtua.support.index'));
        $response->assertSee('Belum ada riwayat dukungan.');
    }

    public function test_unauthorized_access_is_blocked(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $studentUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);

        // Student trying to access Parent Support
        $response = $this->actingAs($studentUser)->get(route('orangtua.support.index'));
        $response->assertStatus(403);
    }
}
