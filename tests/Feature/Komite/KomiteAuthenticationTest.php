<?php

namespace Tests\Feature\Komite;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KomiteAuthenticationTest extends TestCase
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
            'name' => 'SMA Komite Test',
            'email' => 'smakomite@test.com',
            'is_active' => true,
        ]);

        $this->komiteUser = User::create([
            'name' => 'Anggota Komite',
            'email' => 'komite@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $this->komiteRole->id,
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);
    }

    public function test_komite_can_login_with_password_and_redirects_to_komite_dashboard()
    {
        $response = $this->post('/login', [
            'email' => 'komite@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/komite/dashboard');
        $this->assertAuthenticatedAs($this->komiteUser);
    }

    public function test_inactive_komite_user_cannot_login()
    {
        $this->komiteUser->update(['is_active' => false]);

        $response = $this->post('/login', [
            'email' => 'komite@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
