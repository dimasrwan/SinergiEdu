<?php

namespace Tests\Feature\Komite;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKomiteProvisioningTest extends TestCase
{
    use RefreshDatabase;

    protected School $schoolA;
    protected School $schoolB;
    protected User $adminA;
    protected User $adminB;
    protected Role $komiteRole;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $this->komiteRole = Role::firstOrCreate(['name' => 'komite'], ['display_name' => 'Komite Sekolah']);

        $this->schoolA = School::create(['npsn' => '111', 'name' => 'School A', 'is_active' => true]);
        $this->schoolB = School::create(['npsn' => '222', 'name' => 'School B', 'is_active' => true]);

        $this->adminA = User::create([
            'name' => 'Admin A',
            'email' => 'admina@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);

        $this->adminB = User::create([
            'name' => 'Admin B',
            'email' => 'adminb@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'school_id' => $this->schoolB->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_a_can_create_komite_for_school_a()
    {
        $response = $this->actingAs($this->adminA)->post('/admin/komite', [
            'name' => 'Komite Baru A',
            'email' => 'komitea_new@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/admin/komite');

        $this->assertDatabaseHas('users', [
            'name' => 'Komite Baru A',
            'email' => 'komitea_new@test.com',
            'school_id' => $this->schoolA->id,
            'role_id' => $this->komiteRole->id,
        ]);
    }

    public function test_admin_a_cannot_assign_school_id_b_to_new_komite()
    {
        // Admin A attempts to inject school_id = schoolB
        $response = $this->actingAs($this->adminA)->post('/admin/komite', [
            'name' => 'Komite Malicious',
            'email' => 'komite_malicious@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => '1',
            'school_id' => $this->schoolB->id, // Attack
        ]);

        $response->assertRedirect('/admin/komite');

        // Expect school_id to be schoolA, not schoolB
        $this->assertDatabaseHas('users', [
            'email' => 'komite_malicious@test.com',
            'school_id' => $this->schoolA->id,
        ]);
    }

    public function test_admin_a_cannot_edit_komite_of_school_b()
    {
        $komiteB = User::create([
            'name' => 'Komite B Member',
            'email' => 'komiteb_mem@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->komiteRole->id,
            'school_id' => $this->schoolB->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminA)->get("/admin/komite/{$komiteB->id}/edit");
        $this->assertTrue(in_array($response->status(), [403, 404]), 'Admin A cannot edit Komite of School B');
    }
}
