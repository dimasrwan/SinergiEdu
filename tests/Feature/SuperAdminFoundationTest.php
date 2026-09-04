<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Services\TenantService;

class SuperAdminFoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
    }

    public function test_super_admin_can_be_created_with_null_school_id()
    {
        $role = Role::where('name', 'super_admin')->first();
        app(TenantService::class)->setPlatformContext();
        
        $superAdmin = User::create([
            'name' => 'Demo SA',
            'email' => 'sa@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'school_id' => null,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'sa@test.com',
            'school_id' => null,
        ]);
        $this->assertNull($superAdmin->school_id);
    }

    public function test_super_admin_cannot_be_created_with_school_id()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Super Admin must have school_id = NULL');

        $role = Role::where('name', 'super_admin')->first();
        $school = School::create([
            'name' => 'Test School',
            'npsn' => '12345678',
            'address' => 'Test Address',
            'phone' => '08123456789',
            'email' => 'test@school.test',
            'is_active' => true,
        ]);
        
        User::create([
            'name' => 'Demo SA',
            'email' => 'sa2@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'school_id' => $school->id,
        ]);
    }

    public function test_admin_cannot_be_created_with_null_school_id()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Normal user must have a valid school_id');

        $role = Role::where('name', 'admin')->first();
        
        User::create([
            'name' => 'Demo Admin',
            'email' => 'admin_invalid@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'school_id' => null,
        ]);
    }

    public function test_guru_cannot_be_created_with_null_school_id()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Normal user must have a valid school_id');

        $role = Role::where('name', 'guru')->first();
        
        User::create([
            'name' => 'Demo Guru',
            'email' => 'guru_invalid@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'school_id' => null,
        ]);
    }

    public function test_admin_can_be_created_with_valid_school_id()
    {
        $role = Role::where('name', 'admin')->first();
        $school = School::create([
            'name' => 'Test School',
            'npsn' => '12345678',
            'address' => 'Test Address',
            'phone' => '08123456789',
            'email' => 'test@school.test',
            'is_active' => true,
        ]);
        
        app(TenantService::class)->setSchool($school);

        $admin = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin_valid@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'school_id' => $school->id,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin_valid@test.com',
            'school_id' => $school->id,
        ]);
        $this->assertEquals($school->id, $admin->school_id);
    }
}
