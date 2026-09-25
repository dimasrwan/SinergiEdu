<?php

namespace Tests\Feature\Komite;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KomiteAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;
    protected Role $komiteRole;
    protected User $komiteUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->komiteRole = Role::firstOrCreate(['name' => 'komite'], ['display_name' => 'Komite Sekolah']);

        $this->school = School::create([
            'npsn' => '12345678',
            'name' => 'SMA Auth Test',
            'email' => 'auth@test.com',
            'is_active' => true,
        ]);

        $this->komiteUser = User::create([
            'name' => 'Komite Auth',
            'email' => 'komiteauth@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $this->komiteRole->id,
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);
    }

    public function test_komite_can_access_komite_routes()
    {
        $this->actingAs($this->komiteUser)
            ->get('/komite/dashboard')
            ->assertStatus(200);

        $this->actingAs($this->komiteUser)
            ->get('/komite/school-profile')
            ->assertStatus(200);

        $this->actingAs($this->komiteUser)
            ->get('/komite/school-programs')
            ->assertStatus(200);

        $this->actingAs($this->komiteUser)
            ->get('/komite/performance-summary')
            ->assertStatus(200);

        $this->actingAs($this->komiteUser)
            ->get('/komite/aspirations')
            ->assertStatus(200);
    }

    public function test_komite_cannot_access_other_role_routes()
    {
        $forbiddenRoutes = [
            '/admin/dashboard',
            '/admin/teachers',
            '/admin/students',
            '/guru/dashboard',
            '/siswa/dashboard',
            '/orangtua/dashboard',
            '/waka/dashboard',
            '/pengawas/dashboard',
            '/super-admin/dashboard',
        ];

        foreach ($forbiddenRoutes as $route) {
            $response = $this->actingAs($this->komiteUser)->get($route);
            $this->assertTrue(
                in_array($response->status(), [403, 302, 404]),
                "Route $route should be denied for Komite role, got status {$response->status()}"
            );
        }
    }
}
