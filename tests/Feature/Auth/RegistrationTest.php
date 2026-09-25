<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_is_disabled(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_public_users_cannot_self_register(): void
    {
        $role = \App\Models\Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $userCountBefore = \App\Models\User::count();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => $role->id,
        ]);

        $response->assertStatus(404);
        $this->assertGuest();
        $this->assertEquals($userCountBefore, \App\Models\User::count());
    }
}
