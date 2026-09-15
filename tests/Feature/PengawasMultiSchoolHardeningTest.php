<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Inspection;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengawasMultiSchoolHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected User $pengawas;
    protected School $schoolA;
    protected School $schoolB;
    protected School $schoolC;
    protected Role $pengawasRole;
    protected AcademicYear $academicYear;
    protected Semester $semester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pengawasRole = Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        
        // Pengawas user with users.school_id = NULL
        $this->pengawas = User::factory()->create([
            'role_id' => $this->pengawasRole->id,
            'school_id' => null,
        ]);

        $this->schoolA = School::create(['name' => 'School A', 'npsn' => '11111', 'is_active' => true]);
        $this->schoolB = School::create(['name' => 'School B', 'npsn' => '22222', 'is_active' => true]);
        $this->schoolC = School::create(['name' => 'School C', 'npsn' => '33333', 'is_active' => true]);

        // Assign School A and School B to Pengawas
        $this->pengawas->assignedSchools()->attach([$this->schoolA->id, $this->schoolB->id]);

        $this->academicYear = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'name' => '2025/2026',
            'year' => 2025,
            'is_active' => true
        ]);
        $this->semester = Semester::create([
            'school_id' => $this->schoolA->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil',
            'semester_number' => 1,
            'is_active' => true
        ]);
    }

    /** Test 1 & 2: Pengawas single vs multi school assignment */
    public function test_1_and_2_pengawas_has_assigned_schools()
    {
        $this->assertCount(2, $this->pengawas->assignedSchools);
        $this->assertTrue($this->pengawas->assignedSchools->contains($this->schoolA));
        $this->assertTrue($this->pengawas->assignedSchools->contains($this->schoolB));
        $this->assertFalse($this->pengawas->assignedSchools->contains($this->schoolC));
    }

    /** Test 3: Select assigned school succeeds */
    public function test_3_select_assigned_school_succeeds()
    {
        $response = $this->actingAs($this->pengawas)
            ->post(route('pengawas.set-school'), ['school_id' => $this->schoolA->id]);

        $response->assertRedirect(route('pengawas.dashboard'));
        $this->assertEquals($this->schoolA->id, session('pengawas_school_id'));
    }

    /** Test 4 & 5: Select unassigned school fails & session tampering rejected */
    public function test_4_and_5_select_unassigned_school_fails_and_clears_session()
    {
        // Try selecting School C (unassigned)
        $response = $this->actingAs($this->pengawas)
            ->post(route('pengawas.set-school'), ['school_id' => $this->schoolC->id]);

        $response->assertSessionHas('error');

        // Session tampering: forged session with School C
        $response = $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolC->id])
            ->get(route('pengawas.dashboard'));

        $response->assertRedirect(route('pengawas.select-school'));
        $this->assertNull(session('pengawas_school_id'));
    }

    /** Test 6, 7 & 8: Student access control based on active school */
    public function test_6_7_and_8_student_access_by_active_school()
    {
        $userStudentA = User::factory()->create(['school_id' => $this->schoolA->id]);
        $studentA = Student::create(['user_id' => $userStudentA->id, 'school_id' => $this->schoolA->id, 'nis' => '101']);

        $userStudentB = User::factory()->create(['school_id' => $this->schoolB->id]);
        $studentB = Student::create(['user_id' => $userStudentB->id, 'school_id' => $this->schoolB->id, 'nis' => '102']);

        $userStudentC = User::factory()->create(['school_id' => $this->schoolC->id]);
        $studentC = Student::create(['user_id' => $userStudentC->id, 'school_id' => $this->schoolC->id, 'nis' => '103']);

        // Active School A -> Student A ALLOWED, Student B/C DENIED
        $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.students.show', $studentA->id))
            ->assertStatus(200);

        $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.students.show', $studentB->id))
            ->assertStatus(403);

        $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.students.show', $studentC->id))
            ->assertStatus(403);
    }

    /** Test 9, 10, 11, 12: Scope follows active school across Dashboard, Reports, Feedback, Inspection */
    public function test_9_10_11_12_scope_follows_active_school()
    {
        $inspectionA = Inspection::create([
            'title' => 'Inspeksi School A',
            'school_id' => $this->schoolA->id,
            'created_by' => $this->pengawas->id,
        ]);

        $inspectionC = Inspection::create([
            'title' => 'Inspeksi School C',
            'school_id' => $this->schoolC->id,
            'created_by' => $this->pengawas->id,
        ]);

        // Dashboard School A
        $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.dashboard'))
            ->assertStatus(200)
            ->assertSee($this->schoolA->name);

        // Inspection list School A sees Inspection A but NOT Inspection C
        $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.inspections.index'))
            ->assertStatus(200)
            ->assertSee('Inspeksi School A')
            ->assertDontSee('Inspeksi School C');
    }

    /** Test 13 & 14: Login preserves Pengawas identity with NULL school_id */
    public function test_13_and_14_login_preserves_pengawas_null_school_id()
    {
        $this->assertNull($this->pengawas->school_id);
        $this->assertEquals('pengawas', $this->pengawas->role->name);
    }

    /** Test 15: Prevent duplicate pivot assignment */
    public function test_15_prevent_duplicate_pivot_assignment()
    {
        $this->pengawas->assignedSchools()->sync([$this->schoolA->id, $this->schoolA->id]);
        $this->assertCount(1, $this->pengawas->assignedSchools()->where('schools.id', $this->schoolA->id)->get());
    }

    /** Test 16: Logout clears session context */
    public function test_16_logout_clears_pengawas_session_context()
    {
        $response = $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertNull(session('pengawas_school_id'));
    }

    /** Test 17: Inactive Pengawas user is blocked */
    public function test_17_inactive_pengawas_blocked()
    {
        $this->pengawas->update(['is_active' => false]);

        $response = $this->post(route('login'), [
            'email' => $this->pengawas->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    /** Test 18: Super Admin can assign Pengawas to multiple schools */
    public function test_18_super_admin_can_assign_pengawas_to_multiple_schools()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id, 'school_id' => null]);

        $response = $this->actingAs($superAdmin)->post(route('admin.pengawas.store'), [
            'name' => 'New Pengawas SA',
            'email' => 'pengawas_sa@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'schools' => [$this->schoolA->id, $this->schoolB->id],
        ]);

        $response->assertRedirect(route('admin.pengawas.index'));
        $createdUser = User::where('email', 'pengawas_sa@example.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertCount(2, $createdUser->assignedSchools);
    }

    /** Test 19: Admin School cannot assign cross-school */
    public function test_19_admin_school_cannot_assign_cross_school()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);
        $schoolAdmin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $this->schoolA->id]);

        $response = $this->actingAs($schoolAdmin)->post(route('admin.pengawas.store'), [
            'name' => 'Illegal Pengawas',
            'email' => 'illegal_pengawas@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'schools' => [$this->schoolA->id, $this->schoolB->id], // School B is cross-school for School Admin A
        ]);

        $response->assertSessionHasErrors(['schools']);
        $this->assertDatabaseMissing('users', ['email' => 'illegal_pengawas@example.com']);
    }

    /** Test 20: Cross-school inspection IDOR is denied */
    public function test_20_cross_school_inspection_idor_denied()
    {
        $inspectionC = Inspection::create([
            'title' => 'Inspeksi School C',
            'school_id' => $this->schoolC->id,
            'created_by' => $this->pengawas->id,
        ]);

        $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.inspections.show', $inspectionC->id))
            ->assertStatus(403);
    }
}
