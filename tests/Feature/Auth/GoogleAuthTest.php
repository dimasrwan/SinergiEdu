<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function mockGoogleUser(string $email, bool $verified = true, ?string $name = 'Google User'): SocialiteUser
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getEmail')->andReturn($email);
        $socialiteUser->shouldReceive('getName')->andReturn($name);
        $socialiteUser->user = [
            'email_verified' => $verified,
        ];

        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        $facadeMock = Mockery::mock();
        $facadeMock->shouldReceive('driver')->with('google')->andReturn($provider);
        Socialite::swap($facadeMock);

        return $socialiteUser;
    }

    public function test_google_login_button_renders_on_login_screen(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Lanjutkan dengan Google');
        $response->assertSee(route('auth.google'));
    }

    public function test_google_redirect_route_redirects_to_google(): void
    {
        $response = $this->get('/auth/google');

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_registered_active_user_can_login_via_google(): void
    {
        $school = School::create(['name' => 'Test School', 'npsn' => '123456', 'is_active' => true]);
        $role = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $user = User::factory()->create([
            'email' => 'student@school.com',
            'is_active' => true,
            'role_id' => $role->id,
            'school_id' => $school->id,
        ]);

        $this->mockGoogleUser('student@school.com');

        $response = $this->get('/auth/google/callback');

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('siswa.dashboard'));
    }

    public function test_email_normalization_case_insensitive_matching(): void
    {
        $school = School::create(['name' => 'Test School 2', 'npsn' => '654321', 'is_active' => true]);
        $role = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        $user = User::factory()->create([
            'email' => 'teacher@school.com',
            'is_active' => true,
            'role_id' => $role->id,
            'school_id' => $school->id,
        ]);

        $this->mockGoogleUser('Teacher@School.COM');

        $response = $this->get('/auth/google/callback');

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('guru.dashboard'));
    }

    public function test_unregistered_google_email_is_rejected_and_no_user_created(): void
    {
        $initialUserCount = User::count();

        $this->mockGoogleUser('unregistered@school.com');

        $response = $this->get('/auth/google/callback');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertEquals($initialUserCount, User::count());
    }

    public function test_inactive_google_user_is_rejected(): void
    {
        $school = School::create(['name' => 'Test School 3', 'npsn' => '111222', 'is_active' => true]);
        $role = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $user = User::factory()->create([
            'email' => 'inactive@school.com',
            'is_active' => false,
            'role_id' => $role->id,
            'school_id' => $school->id,
        ]);

        $this->mockGoogleUser('inactive@school.com');

        $response = $this->get('/auth/google/callback');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_unverified_google_email_is_rejected(): void
    {
        $school = School::create(['name' => 'Test School 4', 'npsn' => '333444', 'is_active' => true]);
        $role = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        User::factory()->create([
            'email' => 'unverified@school.com',
            'is_active' => true,
            'role_id' => $role->id,
            'school_id' => $school->id,
        ]);

        $this->mockGoogleUser('unverified@school.com', false);

        $response = $this->get('/auth/google/callback');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_all_8_roles_can_authenticate_via_google(): void
    {
        $school = School::create(['name' => 'Test School 5', 'npsn' => '555666', 'is_active' => true]);

        $rolesConfig = [
            'super_admin' => ['school_id' => null, 'dashboard' => 'super_admin.dashboard'],
            'admin' => ['school_id' => $school->id, 'dashboard' => 'admin.dashboard'],
            'guru' => ['school_id' => $school->id, 'dashboard' => 'guru.dashboard'],
            'siswa' => ['school_id' => $school->id, 'dashboard' => 'siswa.dashboard'],
            'orangtua' => ['school_id' => $school->id, 'dashboard' => 'orangtua.dashboard'],
            'waka' => ['school_id' => $school->id, 'dashboard' => 'waka.dashboard'],
            'pengawas' => ['school_id' => $school->id, 'dashboard' => 'pengawas.dashboard'],
            'kepala_sekolah' => ['school_id' => $school->id, 'dashboard' => 'kepala-sekolah.dashboard'],
        ];

        foreach ($rolesConfig as $roleName => $config) {
            $role = Role::firstOrCreate(['name' => $roleName], ['display_name' => ucwords(str_replace('_', ' ', $roleName))]);
            $email = "role_{$roleName}@school.com";

            $user = User::factory()->create([
                'email' => $email,
                'is_active' => true,
                'role_id' => $role->id,
                'school_id' => $config['school_id'],
            ]);

            $this->mockGoogleUser($email);

            $response = $this->get('/auth/google/callback');

            $this->assertAuthenticatedAs($user);
            $response->assertRedirect(route($config['dashboard']));

            $this->post('/logout');
            $this->assertGuest();
        }
    }

    public function test_google_login_handles_cancelled_or_exception_gracefully(): void
    {
        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andThrow(new \Exception('OAuth state invalid or cancelled'));

        $facadeMock = Mockery::mock();
        $facadeMock->shouldReceive('driver')->with('google')->andReturn($provider);
        Socialite::swap($facadeMock);

        $response = $this->get('/auth/google/callback');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_missing_or_null_email_verified_in_google_payload_is_rejected(): void
    {
        $school = School::create(['name' => 'Test School 6', 'npsn' => '777888', 'is_active' => true]);
        $role = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        User::factory()->create([
            'email' => 'nullverified@school.com',
            'is_active' => true,
            'role_id' => $role->id,
            'school_id' => $school->id,
        ]);

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getEmail')->andReturn('nullverified@school.com');
        $socialiteUser->user = []; // email_verified missing

        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        $facadeMock = Mockery::mock();
        $facadeMock->shouldReceive('driver')->with('google')->andReturn($provider);
        Socialite::swap($facadeMock);

        $response = $this->get('/auth/google/callback');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_invalid_tenant_configuration_is_rejected(): void
    {
        $school = School::create(['name' => 'Test School 7', 'npsn' => '999000', 'is_active' => true]);
        $guruRole = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);

        // Create user as super_admin, then force update role_id to guru with school_id = null
        $user = User::factory()->create([
            'email' => 'invalid_tenant@school.com',
            'is_active' => true,
            'role_id' => $superAdminRole->id,
            'school_id' => null,
        ]);
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['role_id' => $guruRole->id]);

        $this->mockGoogleUser('invalid_tenant@school.com');

        $response = $this->get('/auth/google/callback');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }
}
