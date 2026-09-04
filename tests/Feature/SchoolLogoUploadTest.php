<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SchoolLogoUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
    }

    public function test_super_admin_create_school_with_logo()
    {
        Storage::fake('public');

        $superAdminUser = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $file = UploadedFile::fake()->image('logo_baru.jpg', 100, 100)->size(100);

        $response = $this->actingAs($superAdminUser)->post(route('super_admin.schools.store'), [
            'name' => 'New School',
            'is_active' => 1,
            'logo' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        
        $school = School::where('name', 'New School')->first();
        $this->assertNotNull($school->logo);
        Storage::disk('public')->assertExists($school->logo);
    }

    public function test_admin_replace_logo_removes_old_file()
    {
        Storage::fake('public');

        $school = School::create([
            'name' => 'Admin School',
            'is_active' => true,
        ]);

        // Simulasikan logo lama ada
        $oldFile = UploadedFile::fake()->image('old_logo.jpg', 100, 100);
        $oldPath = $oldFile->storeAs('schools/logos', 'old_logo.jpg', 'public');
        $school->update(['logo' => $oldPath]);
        Storage::disk('public')->assertExists($oldPath);

        $adminUser = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->first()->id,
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        $newFile = UploadedFile::fake()->image('new_logo.jpg', 100, 100);

        $response = $this->actingAs($adminUser)->put(route('admin.settings.update'), [
            'school_name' => 'Admin School Updated',
            'school_logo' => $newFile,
        ]);

        $response->assertSessionHasNoErrors();
        
        $school->refresh();
        $this->assertNotNull($school->logo);
        $this->assertNotEquals($oldPath, $school->logo);
        
        Storage::disk('public')->assertExists($school->logo);
        Storage::disk('public')->assertMissing($oldPath); // Pastikan file lama terhapus
    }

    public function test_super_admin_replace_logo_removes_old_file()
    {
        Storage::fake('public');

        $school = School::create([
            'name' => 'Super Admin School',
            'is_active' => true,
        ]);

        $oldFile = UploadedFile::fake()->image('old_sa_logo.jpg', 100, 100);
        $oldPath = $oldFile->storeAs('schools/logos', 'old_sa_logo.jpg', 'public');
        $school->update(['logo' => $oldPath]);

        $superAdminUser = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $newFile = UploadedFile::fake()->image('new_sa_logo.jpg', 100, 100);

        $response = $this->actingAs($superAdminUser)->put(route('super_admin.schools.update', $school), [
            'name' => 'Super Admin School Updated',
            'is_active' => 1,
            'logo' => $newFile,
        ]);

        $response->assertSessionHasNoErrors();
        
        $school->refresh();
        $this->assertNotNull($school->logo);
        $this->assertNotEquals($oldPath, $school->logo);
        
        Storage::disk('public')->assertExists($school->logo);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_edit_without_new_logo_retains_old_logo()
    {
        Storage::fake('public');

        $school = School::create([
            'name' => 'Super Admin School',
            'is_active' => true,
        ]);

        $oldFile = UploadedFile::fake()->image('old_sa_logo.jpg', 100, 100);
        $oldPath = $oldFile->storeAs('schools/logos', 'old_sa_logo.jpg', 'public');
        $school->update(['logo' => $oldPath]);

        $superAdminUser = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        $response = $this->actingAs($superAdminUser)->put(route('super_admin.schools.update', $school), [
            'name' => 'Super Admin School Updated',
            'is_active' => 1,
            // Tidak mengirim 'logo'
        ]);

        $response->assertSessionHasNoErrors();
        
        $school->refresh();
        $this->assertEquals($oldPath, $school->logo); // Harus sama
        Storage::disk('public')->assertExists($oldPath); // File harus tetap ada
    }

    public function test_upload_invalid_logo_retains_old_logo()
    {
        Storage::fake('public');

        $school = School::create([
            'name' => 'Super Admin School',
            'is_active' => true,
        ]);

        $oldFile = UploadedFile::fake()->image('old_sa_logo.jpg', 100, 100);
        $oldPath = $oldFile->storeAs('schools/logos', 'old_sa_logo.jpg', 'public');
        $school->update(['logo' => $oldPath]);

        $superAdminUser = User::factory()->create([
            'role_id' => Role::where('name', 'super_admin')->first()->id,
            'school_id' => null,
            'is_active' => true,
        ]);

        // Upload file bukan gambar (invalid)
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($superAdminUser)->put(route('super_admin.schools.update', $school), [
            'name' => 'Super Admin School Updated',
            'is_active' => 1,
            'logo' => $invalidFile,
        ]);

        // Harus gagal validasi
        $response->assertSessionHasErrors('logo');
        
        $school->refresh();
        $this->assertEquals($oldPath, $school->logo); // Harus sama dengan lama
        Storage::disk('public')->assertExists($oldPath); // File lama tetap aman
    }

    public function test_cross_tenant_admin_cannot_affect_another_school()
    {
        Storage::fake('public');

        $school1 = School::create(['name' => 'School 1', 'is_active' => true]);
        $school2 = School::create(['name' => 'School 2', 'is_active' => true]);

        $oldFile1 = UploadedFile::fake()->image('old1.jpg', 100, 100);
        $oldPath1 = $oldFile1->storeAs('schools/logos', 'old1.jpg', 'public');
        $school1->update(['logo' => $oldPath1]);

        // Admin milik School 2
        $adminUser2 = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->first()->id,
            'school_id' => $school2->id,
            'is_active' => true,
        ]);

        // Admin School 2 mencoba mengubah setting
        $newFile = UploadedFile::fake()->image('new2.jpg', 100, 100);

        // Secara default SettingController memakai Auth::user()->school;
        // Jadi update hanya akan masuk ke school2
        $response = $this->actingAs($adminUser2)->put(route('admin.settings.update'), [
            'school_name' => 'School 2 Updated',
            'school_logo' => $newFile,
        ]);

        $response->assertSessionHasNoErrors();
        
        $school1->refresh();
        // Pastikan school 1 tidak terpengaruh
        $this->assertEquals($oldPath1, $school1->logo);
        Storage::disk('public')->assertExists($oldPath1);
    }
}
