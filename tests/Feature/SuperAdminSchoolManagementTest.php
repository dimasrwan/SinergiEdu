<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Services\TenantService;

class SuperAdminSchoolManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
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
        ]);
        app(TenantService::class)->clear();
        return $user;
    }

    private function createSchoolAdmin($school)
    {
        app(TenantService::class)->setSchool($school);
        $user = User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin_'.$school->id.'@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'admin')->first()->id,
            'school_id' => $school->id,
        ]);
        app(TenantService::class)->clear();
        return $user;
    }

    public function test_super_admin_can_access_school_management()
    {
        $sa = $this->createSuperAdmin();
        
        $response = $this->actingAs($sa)->get(route('super_admin.schools.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.super-admin.schools.index');
    }

    public function test_admin_sekolah_cannot_access_super_admin_routes()
    {
        $school = School::create(['name' => 'Test School', 'is_active' => true]);
        $admin = $this->createSchoolAdmin($school);

        $response = $this->actingAs($admin)->get(route('super_admin.schools.index'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_school()
    {
        $sa = $this->createSuperAdmin();

        $response = $this->actingAs($sa)->post(route('super_admin.schools.store'), [
            'name' => 'Sekolah Baru',
            'npsn' => '12345678',
            'email' => 'sekolah@baru.test',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('super_admin.schools.index'));
        $this->assertDatabaseHas('schools', [
            'name' => 'Sekolah Baru',
            'npsn' => '12345678',
            'is_active' => 1,
        ]);
    }

    public function test_super_admin_can_toggle_school_status()
    {
        $sa = $this->createSuperAdmin();
        $school = School::create(['name' => 'Sekolah 1', 'is_active' => true]);

        $response = $this->actingAs($sa)->patch(route('super_admin.schools.toggle-status', $school), [
            'is_active' => 0,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
            'is_active' => 0,
        ]);
    }

    public function test_inactive_school_blocks_tenant_login()
    {
        $school = School::create(['name' => 'Sekolah Nonaktif', 'is_active' => false]);
        $admin = $this->createSchoolAdmin($school);

        $response = $this->actingAs($admin)->get('/dashboard');
        
        // Either redirect to login or throw 403 Forbidden.
        // In our implementation TenantMiddleware throws abort(403)
        $response->assertStatus(403);
    }
}
