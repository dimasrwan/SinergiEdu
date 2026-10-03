<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SecurityRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_get_private_storage()
    {
        // 403 or 404 is expected because guest does not provide valid signature
        $response = $this->get('/storage/test.txt');
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_guest_cannot_put_storage_without_signature()
    {
        $response = $this->put('/storage/test.txt', ['content' => 'test']);
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_put_upload_true_without_signature_denied()
    {
        $response = $this->put('/storage/test.txt?upload=true', ['content' => 'test']);
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_get_path_traversal_denied()
    {
        $response = $this->get('/storage/..%2F..%2F.env');
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_put_path_traversal_denied()
    {
        $response = $this->put('/storage/..%2F..%2F.env?upload=true', ['content' => 'hack']);
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_komite_cannot_access_waka_endpoints()
    {
        $roleId = \Illuminate\Support\Facades\DB::table('roles')->where('name', 'komite')->value('id');
        if (!$roleId) {
            $roleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId(['name' => 'komite', 'display_name' => 'Komite Sekolah']);
        }
        $schoolId = \App\Models\School::insertGetId(['name' => 'School A']);
        $komiteUser = User::forceCreate([
            'name' => 'Komite',
            'email' => 'komite@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleId, // Komite
            'school_id' => $schoolId,
            'is_active' => true,
        ]);

        $this->actingAs($komiteUser);
        
        // Coba akses waka dashboard
        $response = $this->get('/waka/dashboard');
        $this->assertTrue(in_array($response->status(), [403, 404]));

        // Coba akses waka monitoring
        $response = $this->get('/waka/monitoring/grades');
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }
}
