<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\School;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $roles = [
            'super_admin', 'admin', 'guru', 'siswa', 
            'orangtua', 'waka', 'pengawas', 'kepala_sekolah'
        ];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role], ['display_name' => ucfirst(str_replace('_', ' ', $role))]);
        }
    }

    public function test_user_without_photo_sees_initials_and_null_url()
    {
        $superAdmin = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $this->assertNull($superAdmin->profilePhotoUrl());
    }

    public function test_user_can_upload_valid_photo()
    {
        Storage::fake('public');

        $superAdmin = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(100);

        $response = $this->actingAs($superAdmin)->post(route('profile.photo.update'), [
            'photo' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        
        $superAdmin->refresh();
        $this->assertNotNull($superAdmin->profile_photo_path);
        Storage::disk('public')->assertExists($superAdmin->profile_photo_path);
    }

    public function test_user_can_replace_photo_and_old_is_deleted()
    {
        Storage::fake('public');

        $superAdmin = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $oldFile = UploadedFile::fake()->image('old_avatar.jpg', 100, 100);
        $oldPath = $oldFile->storeAs('profile-photos', 'old_avatar.jpg', 'public');
        $superAdmin->update(['profile_photo_path' => $oldPath]);
        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->image('new_avatar.jpg', 100, 100);

        $response = $this->actingAs($superAdmin)->post(route('profile.photo.update'), [
            'photo' => $newFile,
        ]);

        $response->assertSessionHasNoErrors();
        
        $superAdmin->refresh();
        $this->assertNotNull($superAdmin->profile_photo_path);
        $this->assertNotEquals($oldPath, $superAdmin->profile_photo_path);
        
        Storage::disk('public')->assertExists($superAdmin->profile_photo_path);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_user_can_delete_photo()
    {
        Storage::fake('public');

        $superAdmin = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $oldFile = UploadedFile::fake()->image('old_avatar.jpg', 100, 100);
        $oldPath = $oldFile->storeAs('profile-photos', 'old_avatar.jpg', 'public');
        $superAdmin->update(['profile_photo_path' => $oldPath]);

        $response = $this->actingAs($superAdmin)->delete(route('profile.photo.destroy'));

        $response->assertSessionHasNoErrors();
        
        $superAdmin->refresh();
        $this->assertNull($superAdmin->profile_photo_path);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_invalid_file_rejected()
    {
        Storage::fake('public');

        $superAdmin = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($superAdmin)->post(route('profile.photo.update'), [
            'photo' => $file,
        ]);

        $response->assertSessionHasErrors('photo');
        
        $superAdmin->refresh();
        $this->assertNull($superAdmin->profile_photo_path);
    }

    public function test_all_roles_can_upload_photo()
    {
        Storage::fake('public');

        $school = School::create(['name' => 'Test School', 'is_active' => true]);

        $roles = [
            'admin', 'guru', 'siswa', 
            'orangtua', 'waka', 'pengawas', 'kepala_sekolah'
        ];

        foreach ($roles as $roleName) {
            $user = User::factory()->create([
                'role_id' => Role::where('name', $roleName)->first()->id,
                'school_id' => $school->id,
                'is_active' => true,
            ]);

            $file = UploadedFile::fake()->image("{$roleName}_avatar.jpg", 100, 100);

            $response = $this->actingAs($user)->post(route('profile.photo.update'), [
                'photo' => $file,
            ]);

            $response->assertSessionHasNoErrors();
            
            $user->refresh();
            $this->assertNotNull($user->profile_photo_path);
            Storage::disk('public')->assertExists($user->profile_photo_path);
        }
    }
}
