<?php

namespace Tests\Feature\Komite;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminKomiteProvisioningTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;
    protected User $superAdmin;
    protected Role $komiteRole;

    protected function setUp(): void
    {
        parent::setUp();

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $this->komiteRole = Role::firstOrCreate(['name' => 'komite'], ['display_name' => 'Komite Sekolah']);

        $this->school = School::create(['npsn' => '999999', 'name' => 'School Super Test', 'is_active' => true]);

        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);
    }

    public function test_super_admin_can_create_komite_for_any_school()
    {
        $response = $this->actingAs($this->superAdmin)
            ->post("/super-admin/schools/{$this->school->id}/komite", [
                'name' => 'Komite Super Provision',
                'email' => 'komitesuper@test.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'is_active' => '1',
            ]);

        $response->assertRedirect("/super-admin/schools/{$this->school->id}");

        $this->assertDatabaseHas('users', [
            'name' => 'Komite Super Provision',
            'email' => 'komitesuper@test.com',
            'school_id' => $this->school->id,
            'role_id' => $this->komiteRole->id,
        ]);
    }

    public function test_super_admin_can_toggle_komite_status()
    {
        $komite = User::create([
            'name' => 'Komite Toggle',
            'email' => 'komitetoggle@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->komiteRole->id,
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->patch("/super-admin/schools/{$this->school->id}/komite/{$komite->id}/toggle-status", [
                'is_active' => '0',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $komite->id,
            'is_active' => false,
        ]);
    }
}
