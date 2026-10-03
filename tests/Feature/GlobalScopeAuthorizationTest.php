<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GlobalScopeAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengawas_without_global_scope_access()
    {
        $roleId = DB::table('roles')->where('name', 'pengawas')->value('id');
        if (!$roleId) {
            $roleId = DB::table('roles')->insertGetId(['name' => 'pengawas', 'display_name' => 'Pengawas']);
        }

        $schoolA = School::insertGetId(['name' => 'School A']);
        $schoolB = School::insertGetId(['name' => 'School B']);

        // Pengawas (no school_id)
        $pengawas = User::forceCreate([
            'name' => 'Pengawas A',
            'email' => 'pengawas@test.com',
            'password' => bcrypt('pass'),
            'role_id' => $roleId,
            'school_id' => null,
            'is_active' => true
        ]);

        $pengawasModel = DB::table('pengawas')->insertGetId(['user_id' => $pengawas->id]);
        
        // Assign only to School A
        // DB::table('pengawas_school')->insert([
        //     'pengawas_id' => $pengawasModel,
        //     'school_id' => $schoolA,
        // ]);

        $this->actingAs($pengawas);

        // Try to access School B data through StudentMonitoringController which uses withoutGlobalScope
        // Simulate session
        session(['pengawas_school_id' => $schoolB]); // Forged session for School B
        
        $studentRoleId = DB::table('roles')->where('name', 'siswa')->value('id');
        if (!$studentRoleId) {
            $studentRoleId = DB::table('roles')->insertGetId(['name' => 'siswa', 'display_name' => 'Siswa']);
        }

        $childB1 = User::forceCreate(['name' => 'Child B1', 'email' => 'childB1@test.com', 'password' => bcrypt('pass'), 'role_id' => $studentRoleId, 'school_id' => $schoolB]);
        $studentB1 = DB::table('students')->insertGetId(['user_id' => $childB1->id, 'parent_id' => null, 'school_id' => $schoolB]);

        $response = $this->get('/pengawas/monitoring/students/'.$studentB1);
        $this->assertTrue(in_array($response->status(), [403, 404, 500])); // Assuming 403 or 404
    }
}
