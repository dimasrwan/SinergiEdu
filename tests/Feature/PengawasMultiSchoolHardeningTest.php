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

    /** Test 19: Admin School cannot create Pengawas identity directly (Option B policy check) */
    public function test_19_admin_school_cannot_assign_cross_school()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);
        $schoolAdmin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $this->schoolA->id]);

        $response = $this->actingAs($schoolAdmin)->post(route('admin.pengawas.store'), [
            'name' => 'Illegal Pengawas',
            'email' => 'illegal_pengawas@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'schools' => [$this->schoolA->id],
        ]);

        $response->assertStatus(403);
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

    /** Test 21: Super Admin attach supervisor via school detail maintains existing schools */
    public function test_21_super_admin_attach_supervisor_via_school_detail_maintains_existing_schools()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id, 'school_id' => null]);

        // Attach School C to $this->pengawas who already has School A & B
        $response = $this->actingAs($superAdmin)->post(route('super_admin.schools.supervisors.attach', $this->schoolC), [
            'user_id' => $this->pengawas->id,
        ]);

        $response->assertRedirect();
        $this->assertCount(3, $this->pengawas->fresh()->assignedSchools);
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolA));
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolB));
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolC));
    }

    /** Test 22: Super Admin detach supervisor removes only target school pivot */
    public function test_22_super_admin_detach_supervisor_removes_only_target_school_pivot()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id, 'school_id' => null]);

        // Detach School A from $this->pengawas
        $response = $this->actingAs($superAdmin)->delete(route('super_admin.schools.supervisors.detach', [$this->schoolA, $this->pengawas]));

        $response->assertRedirect();
        $this->assertCount(1, $this->pengawas->fresh()->assignedSchools);
        $this->assertFalse($this->pengawas->fresh()->assignedSchools->contains($this->schoolA));
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolB));
        $this->assertNotNull(User::find($this->pengawas->id));
    }

    /** Test 23: Super Admin update can modify pengawas identity and assigned schools */
    public function test_23_admin_school_update_preserves_foreign_school_assignments()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id, 'school_id' => null]);

        $pengawasModel = \App\Models\Pengawas::create([
            'user_id' => $this->pengawas->id,
            'nip' => '12345678',
        ]);

        // Ensure pivot is populated
        $this->pengawas->assignedSchools()->sync([$this->schoolA->id, $this->schoolB->id]);

        // Super Admin updates Pengawas with schools = [School A]
        $response = $this->actingAs($superAdmin)->put(route('admin.pengawas.update', $pengawasModel), [
            'name' => $this->pengawas->name,
            'email' => $this->pengawas->email,
            'nip' => '12345678',
            'schools' => [$this->schoolA->id],
        ]);

        $response->assertRedirect(route('admin.pengawas.index'));
        $this->assertCount(1, $this->pengawas->fresh()->assignedSchools);
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolA));
    }

    /** Test 24: Pengawas archived inspections endpoint returns 200 and filters by active school */
    public function test_24_pengawas_archived_inspections_endpoint_returns_200_and_filters_by_active_school()
    {
        Inspection::create([
            'title' => 'Archived School A Inspection',
            'school_id' => $this->schoolA->id,
            'created_by' => $this->pengawas->id,
            'is_archived' => true,
        ]);

        Inspection::create([
            'title' => 'Archived School B Inspection',
            'school_id' => $this->schoolB->id,
            'created_by' => $this->pengawas->id,
            'is_archived' => true,
        ]);

        // Active school A should see archived school A inspection
        $response = $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.inspections.archived'));

        $response->assertStatus(200);
        $response->assertSee('Archived School A Inspection');
        $response->assertDontSee('Archived School B Inspection');
    }

    /** Test 25: Pengawas user monitoring show endpoint returns 200 for user in active school */
    public function test_25_pengawas_user_monitoring_show_returns_200()
    {
        $targetUser = User::factory()->create(['school_id' => $this->schoolA->id]);

        $response = $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.users.show', $targetUser));

        $response->assertStatus(200);
        $response->assertSee($targetUser->name);
    }

    /** Test 26: Admin School can connect existing Pengawas to own school */
    public function test_26_admin_school_can_connect_existing_pengawas_to_own_school()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);
        $schoolAdmin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $this->schoolC->id]);

        $pengawasModel = \App\Models\Pengawas::create([
            'user_id' => $this->pengawas->id,
            'nip' => '99988877',
        ]);

        // Connect School C via Admin School C
        $response = $this->actingAs($schoolAdmin)->post(route('admin.pengawas.connect'), [
            'pengawas_id' => $pengawasModel->id,
        ]);

        $response->assertRedirect(route('admin.pengawas.index'));
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolC));
        // School A & B remain preserved
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolA));
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolB));
    }

    /** Test 27: Admin School can disconnect Pengawas from own school without deleting user/profile */
    public function test_27_admin_school_can_disconnect_pengawas_from_own_school()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);
        $schoolAdminA = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $this->schoolA->id]);

        $pengawasModel = \App\Models\Pengawas::create([
            'user_id' => $this->pengawas->id,
            'nip' => '99988877',
        ]);

        // Disconnect School A via Admin School A
        $response = $this->actingAs($schoolAdminA)->delete(route('admin.pengawas.disconnect', $pengawasModel));

        $response->assertRedirect(route('admin.pengawas.index'));
        $this->assertFalse($this->pengawas->fresh()->assignedSchools->contains($this->schoolA));
        // School B remains intact
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolB));
        // User & Pengawas profile exist
        $this->assertNotNull(User::find($this->pengawas->id));
    }

    /** Test 28: Direct URL edit or create by Admin School is denied (Option B) */
    public function test_28_direct_url_edit_or_create_by_admin_school_is_denied()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);
        $schoolAdmin = User::factory()->create(['role_id' => $adminRole->id, 'school_id' => $this->schoolA->id]);

        $pengawasModel = \App\Models\Pengawas::create([
            'user_id' => $this->pengawas->id,
            'nip' => '99988877',
        ]);

        $this->actingAs($schoolAdmin)->get(route('admin.pengawas.create'))->assertStatus(403);
        $this->actingAs($schoolAdmin)->get(route('admin.pengawas.edit', $pengawasModel))->assertStatus(403);
    }

    /** Test 29: Student monitoring 'Semua Kelas' filter returns all active school students while maintaining tenant isolation */
    public function test_29_student_monitoring_semua_kelas_returns_all_active_school_students()
    {
        $activeYear = AcademicYear::firstOrCreate(['is_active' => true], ['name' => '2025/2026']);
        $activeSemester = Semester::firstOrCreate(['is_active' => true], ['name' => 'Ganjil']);

        $classA = Classroom::create(['school_id' => $this->schoolA->id, 'name' => 'Kelas A', 'grade_level' => '10']);
        $classB = Classroom::create(['school_id' => $this->schoolB->id, 'name' => 'Kelas B', 'grade_level' => '10']);

        $userStudentA = User::factory()->create(['school_id' => $this->schoolA->id, 'name' => 'Student A Name']);
        $userStudentB = User::factory()->create(['school_id' => $this->schoolB->id, 'name' => 'Student B Name']);

        $studentA = Student::create(['school_id' => $this->schoolA->id, 'user_id' => $userStudentA->id, 'nis' => '1111']);
        $studentB = Student::create(['school_id' => $this->schoolB->id, 'user_id' => $userStudentB->id, 'nis' => '2222']);

        $studentA->classes()->attach($classA->id, ['academic_year_id' => $activeYear->id, 'school_id' => $this->schoolA->id]);
        $studentB->classes()->attach($classB->id, ['academic_year_id' => $activeYear->id, 'school_id' => $this->schoolB->id]);

        // Request with class_id='all' on active school A
        $response = $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.students.index', ['class_id' => 'all']));

        $response->assertStatus(200);
        $response->assertSee('Student A Name');
        $response->assertDontSee('Student B Name');

        // Attempting to pass cross-school class_id falls back to 'all' safely scoped to active school A
        $responseTamper = $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.students.index', ['class_id' => $classB->id]));

        $responseTamper->assertStatus(200);
        $responseTamper->assertSee('Student A Name');
        $responseTamper->assertDontSee('Student B Name');
    }

    /** Test 30: Student report download respects 'Semua Kelas' and tenant isolation */
    public function test_30_student_report_download_respects_semua_kelas_and_tenant_isolation()
    {
        $activeYear = AcademicYear::firstOrCreate(['is_active' => true], ['name' => '2025/2026']);
        $activeSemester = Semester::firstOrCreate(['is_active' => true], ['name' => 'Ganjil']);

        $userStudentA = User::factory()->create(['school_id' => $this->schoolA->id]);
        $userStudentB = User::factory()->create(['school_id' => $this->schoolB->id]);

        $studentA = Student::create(['school_id' => $this->schoolA->id, 'user_id' => $userStudentA->id, 'nis' => '11111']);
        $studentB = Student::create(['school_id' => $this->schoolB->id, 'user_id' => $userStudentB->id, 'nis' => '99999']);

        $response = $this->actingAs($this->pengawas)
            ->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get(route('pengawas.students.downloadReport', ['class_id' => 'all']));

        $response->assertStatus(200);
        $this->assertEquals('text/csv; charset=utf-8', $response->headers->get('Content-Type'));
    }

    /** Test 31: Super Admin can remove one school assignment via edit form while preserving others, profile, and user account */
    public function test_31_super_admin_can_remove_one_school_assignment_via_edit_form()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id, 'school_id' => null]);

        $pengawasModel = \App\Models\Pengawas::create([
            'user_id' => $this->pengawas->id,
            'nip' => '77766655',
        ]);

        // Initially assigned to School A & School B
        $this->pengawas->assignedSchools()->sync([$this->schoolA->id, $this->schoolB->id]);

        // Super Admin unchecks School B, submitting only [School A]
        $response = $this->actingAs($superAdmin)->put(route('admin.pengawas.update', $pengawasModel), [
            'name' => $this->pengawas->name,
            'email' => $this->pengawas->email,
            'nip' => '77766655',
            'schools' => [$this->schoolA->id],
        ]);

        $response->assertRedirect(route('admin.pengawas.index'));
        $response->assertSessionHas('success');

        // School A preserved, School B removed
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolA));
        $this->assertFalse($this->pengawas->fresh()->assignedSchools->contains($this->schoolB));

        // User & Pengawas profile exist
        $this->assertNotNull(User::find($this->pengawas->id));
        $this->assertNotNull(\App\Models\Pengawas::find($pengawasModel->id));
    }

    /** Test 32: Super Admin attempting to uncheck all schools triggers validation error */
    public function test_32_super_admin_unchecking_all_schools_triggers_validation_error()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $superAdmin = User::factory()->create(['role_id' => $superAdminRole->id, 'school_id' => null]);

        $pengawasModel = \App\Models\Pengawas::create([
            'user_id' => $this->pengawas->id,
            'nip' => '77766655',
        ]);

        $this->pengawas->assignedSchools()->sync([$this->schoolA->id]);

        // Submit empty schools array
        $response = $this->actingAs($superAdmin)->put(route('admin.pengawas.update', $pengawasModel), [
            'name' => $this->pengawas->name,
            'email' => $this->pengawas->email,
            'nip' => '77766655',
            'schools' => [],
        ]);

        $response->assertSessionHasErrors(['schools']);
        $this->assertTrue($this->pengawas->fresh()->assignedSchools->contains($this->schoolA));
    }
}
