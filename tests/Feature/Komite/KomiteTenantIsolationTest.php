<?php

namespace Tests\Feature\Komite;

use App\Models\KomiteAspiration;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KomiteTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected School $schoolA;
    protected School $schoolB;
    protected User $komiteA;
    protected User $komiteB;

    protected function setUp(): void
    {
        parent::setUp();

        $roleKomite = Role::firstOrCreate(['name' => 'komite'], ['display_name' => 'Komite Sekolah']);

        $this->schoolA = School::create([
            'npsn' => '11111111',
            'name' => 'School A',
            'email' => 'a@test.com',
            'is_active' => true,
        ]);

        $this->schoolB = School::create([
            'npsn' => '22222222',
            'name' => 'School B',
            'email' => 'b@test.com',
            'is_active' => true,
        ]);

        $this->komiteA = User::create([
            'name' => 'Komite A',
            'email' => 'komitea@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $roleKomite->id,
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);

        $this->komiteB = User::create([
            'name' => 'Komite B',
            'email' => 'komiteb@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $roleKomite->id,
            'school_id' => $this->schoolB->id,
            'is_active' => true,
        ]);
    }

    public function test_komite_a_cannot_read_aspirations_of_school_b()
    {
        // Set tenant context to B to create B aspiration
        app(TenantService::class)->setSchool($this->schoolB);
        $aspirationB = KomiteAspiration::create([
            'user_id' => $this->komiteB->id,
            'title' => 'Aspirasi B',
            'content' => 'Isi Aspirasi B',
            'status' => 'pending',
        ]);

        // Komite A attempts to view aspiration B
        $response = $this->actingAs($this->komiteA)->get("/komite/aspirations/{$aspirationB->id}");
        $this->assertTrue(in_array($response->status(), [403, 404]), 'Should deny cross-tenant access with 403 or 404');
    }

    public function test_query_parameter_school_id_cannot_switch_tenant_context()
    {
        $response = $this->actingAs($this->komiteA)
            ->get("/komite/dashboard?school_id={$this->schoolB->id}");

        $response->assertStatus(200);
        $this->assertEquals($this->schoolA->id, app(TenantService::class)->getSchoolId());
    }
}
