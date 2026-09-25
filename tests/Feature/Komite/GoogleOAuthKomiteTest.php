<?php

namespace Tests\Feature\Komite;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class GoogleOAuthKomiteTest extends TestCase
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
            'npsn' => '777777',
            'name' => 'School Google Test',
            'is_active' => true,
        ]);

        $this->komiteUser = User::create([
            'name' => 'Komite Google',
            'email' => 'komitegoogle@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $this->komiteRole->id,
            'school_id' => $this->school->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_google_oauth_login_succeeds_for_existing_verified_komite_account()
    {
        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-123');
        $abstractUser->shouldReceive('getEmail')->andReturn('komitegoogle@test.com');
        $abstractUser->shouldReceive('getName')->andReturn('Komite Google');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.url');
        $abstractUser->user = ['email_verified' => true];

        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/komite/dashboard');
        $this->assertAuthenticatedAs($this->komiteUser);
    }

    public function test_google_oauth_login_fails_for_unregistered_email()
    {
        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-999');
        $abstractUser->shouldReceive('getEmail')->andReturn('unknown@test.com');
        $abstractUser->shouldReceive('getName')->andReturn('Unknown User');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.url');
        $abstractUser->user = ['email_verified' => true];

        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
