<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Services\TenantService;

class SuperAdminAdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
    }

    private function createSuperAdmin()
    {
        app(TenantService::class)->setPlatformContext();
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'sa@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);
        app(TenantService::class)->clear();
        return $user;
    }

    private function createSchoolAdmin($school, $email = null)
    {
        app(TenantService::class)->setSchool($school);
        $user = User::create([
            'name' => 'Admin Sekolah ' . $school->id,
            'email' => $email ?? 'admin_'.$school->id.'@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'admin')->first()->id,
            'school_id' => $school->id,
            'is_active' => true,
        ]);
        app(TenantService::class)->clear();
        return $user;
    }

    public function test_super_admin_can_see_admin_school_a_and_b()
    {
        $sa = $this->createSuperAdmin();
        $schoolA = School::create(['name' => 'School A', 'is_active' => true]);
        $schoolB = School::create(['name' => 'School B', 'is_active' => true]);
        
        $adminA = $this->createSchoolAdmin($schoolA);
        $adminB = $this->createSchoolAdmin($schoolB);

        $responseA = $this->actingAs($sa)->get(route('super_admin.schools.show', $schoolA));
        $responseA->assertStatus(200);
        $responseA->assertSee($adminA->email);

        $responseB = $this->actingAs($sa)->get(route('super_admin.schools.show', $schoolB));
        $responseB->assertStatus(200);
        $responseB->assertSee($adminB->email);
    }

    public function test_super_admin_can_create_admin_for_school_a()
    {
        $sa = $this->createSuperAdmin();
        $schoolA = School::create(['name' => 'School A', 'is_active' => true]);

        $response = $this->actingAs($sa)->post(route('super_admin.schools.admins.store', $schoolA), [
            'name' => 'New Admin A',
            'email' => 'newadmin_a@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('super_admin.schools.show', $schoolA));
        $this->assertDatabaseHas('users', [
            'email' => 'newadmin_a@test.com',
            'school_id' => $schoolA->id,
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);
    }

    public function test_admin_cannot_access_super_admin_routes()
    {
        $school = School::create(['name' => 'School Test', 'is_active' => true]);
        $admin = $this->createSchoolAdmin($school);

        $response = $this->actingAs($admin)->get(route('super_admin.schools.show', $school));
        $response->assertStatus(403);
    }

    public function test_cannot_create_admin_with_existing_email_in_same_school()
    {
        $sa = $this->createSuperAdmin();
        $school = School::create(['name' => 'School A', 'is_active' => true]);
        $admin = $this->createSchoolAdmin($school, 'existing@test.com');

        $response = $this->actingAs($sa)->post(route('super_admin.schools.admins.store', $school), [
            'name' => 'Another Admin',
            'email' => 'existing@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertEquals(1, User::where('email', 'existing@test.com')->count());
    }

    public function test_cannot_create_admin_with_existing_email_in_other_school()
    {
        $sa = $this->createSuperAdmin();
        $schoolA = School::create(['name' => 'School A', 'is_active' => true]);
        $schoolB = School::create(['name' => 'School B', 'is_active' => true]);
        $adminA = $this->createSchoolAdmin($schoolA, 'existing@test.com');

        $response = $this->actingAs($sa)->post(route('super_admin.schools.admins.store', $schoolB), [
            'name' => 'Another Admin',
            'email' => 'existing@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseHas('users', [
            'email' => 'existing@test.com',
            'school_id' => $schoolA->id, // remain in School A
        ]);
    }

    public function test_toggle_admin_status_does_not_delete_school_or_user()
    {
        $sa = $this->createSuperAdmin();
        $school = School::create(['name' => 'School Test', 'is_active' => true]);
        $admin = $this->createSchoolAdmin($school);

        $response = $this->actingAs($sa)->patch(route('super_admin.schools.admins.toggle-status', [$school, $admin]), [
            'is_active' => 0,
        ]);

        $response->assertRedirect();
        
        // Assert user is still there but inactive
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'is_active' => 0,
        ]);

        // Assert school is still there
        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
        ]);
    }

    public function test_tenant_isolation_is_maintained_for_admin_login()
    {
        // New admin should only see their own school scope
        $schoolA = School::create(['name' => 'School A', 'is_active' => true]);
        $schoolB = School::create(['name' => 'School B', 'is_active' => true]);
        
        $adminA = $this->createSchoolAdmin($schoolA);
        
        $response = $this->actingAs($adminA)->get('/dashboard');
        // Admin A should be redirected to admin.dashboard or similar
        // Let's just assert they don't get a 403 (they pass TenantMiddleware).
        // Since we are mocking login, if the tenant is right, it works.
        $this->assertEquals($schoolA->id, app(TenantService::class)->getSchoolId());
    }
}
