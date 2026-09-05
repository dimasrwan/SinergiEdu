<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPreference;
use App\Models\Role;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // create basic roles
        Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        Role::firstOrCreate(['name' => 'orangtua'], ['display_name' => 'Orang Tua']);
        Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka']);
        Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
    }

    protected function createSchool()
    {
        return School::firstOrCreate(
            ['npsn' => '12345678'],
            ['name' => 'Sekolah Test', 'address' => 'Jl. Test']
        );
    }

    public function test_authenticated_user_can_access_settings()
    {
        $school = $this->createSchool();
        $user = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'guru')->first()->id]);

        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan');
        $response->assertSee('Tampilan');
    }

    public function test_guest_denied_access_to_settings()
    {
        $response = $this->get('/settings');
        $response->assertRedirect('/login');
    }

    public function test_default_preferences_created_on_access()
    {
        $school = $this->createSchool();
        $user = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'siswa')->first()->id]);
        
        $this->assertNull($user->preferences);

        $this->actingAs($user)->get('/settings');

        $user->refresh();
        $this->assertNotNull($user->preferences);
        $this->assertEquals('system', $user->preferences->theme);
        $this->assertTrue($user->preferences->email_notifications);
        $this->assertTrue($user->preferences->push_notifications);
    }

    public function test_user_can_save_light_theme()
    {
        $school = $this->createSchool();
        $user = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'guru')->first()->id]);

        $response = $this->actingAs($user)->put('/settings/preferences', [
            'theme' => 'light',
            'email_notifications' => true,
            'push_notifications' => true,
        ]);

        $response->assertRedirect(route('settings.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('light', $user->fresh()->preferences->theme);
    }

    public function test_user_can_save_dark_theme()
    {
        $school = $this->createSchool();
        $user = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'guru')->first()->id]);

        $this->actingAs($user)->put('/settings/preferences', [
            'theme' => 'dark',
        ]);

        $this->assertEquals('dark', $user->fresh()->preferences->theme);
        $this->assertFalse($user->fresh()->preferences->email_notifications);
        $this->assertFalse($user->fresh()->preferences->push_notifications);
    }

    public function test_user_can_save_system_theme()
    {
        $school = $this->createSchool();
        $user = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'guru')->first()->id]);

        $this->actingAs($user)->put('/settings/preferences', [
            'theme' => 'system',
        ]);

        $this->assertEquals('system', $user->fresh()->preferences->theme);
    }

    public function test_invalid_theme_is_rejected()
    {
        $school = $this->createSchool();
        $user = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'guru')->first()->id]);

        $response = $this->actingAs($user)->put('/settings/preferences', [
            'theme' => 'invalid_theme',
        ]);

        $response->assertSessionHasErrors('theme');
    }

    public function test_user_cannot_edit_another_users_preference()
    {
        $school = $this->createSchool();
        $userA = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'guru')->first()->id]);
        $userB = User::factory()->create(['school_id' => $school->id, 'role_id' => Role::where('name', 'guru')->first()->id]);

        // Attempting to inject user_id in the payload
        $this->actingAs($userA)->put('/settings/preferences', [
            'theme' => 'dark',
            'user_id' => $userB->id, // Should be ignored
        ]);

        $this->assertEquals('dark', $userA->fresh()->preferences->theme);
        $this->assertNull($userB->fresh()->preferences);
    }

    public function test_super_admin_works_with_school_id_null()
    {
        $superAdmin = User::factory()->create([
            'school_id' => null, 
            'role_id' => Role::where('name', 'super_admin')->first()->id
        ]);

        $response = $this->actingAs($superAdmin)->get('/settings');
        $response->assertStatus(200);

        $this->actingAs($superAdmin)->put('/settings/preferences', [
            'theme' => 'dark',
        ]);

        $this->assertEquals('dark', $superAdmin->fresh()->preferences->theme);
    }

    public function test_all_8_roles_can_access()
    {
        $school = $this->createSchool();
        $roles = [
            'super_admin' => null,
            'admin' => $school->id,
            'guru' => $school->id,
            'siswa' => $school->id,
            'orangtua' => $school->id,
            'waka' => $school->id,
            'pengawas' => $school->id,
            'kepala_sekolah' => $school->id,
        ];

        foreach ($roles as $roleName => $schoolId) {
            $user = User::factory()->create([
                'school_id' => $schoolId,
                'role_id' => Role::where('name', $roleName)->first()->id
            ]);

            $response = $this->actingAs($user)->get('/settings');
            $response->assertStatus(200);
        }
    }
}
