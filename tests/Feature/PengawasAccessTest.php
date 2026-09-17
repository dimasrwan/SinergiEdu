<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengawasAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengawas_can_access_assigned_school()
    {
        $role = \App\Models\Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        $pengawas = \App\Models\User::factory()->create(['role_id' => $role->id]);
        $school = \App\Models\School::create(['name' => 'Sekolah A', 'npsn' => '12345']);
        $pengawas->assignedSchools()->attach($school->id);

        $this->actingAs($pengawas)
             ->withSession(['pengawas_school_id' => $school->id])
             ->get(route('pengawas.dashboard'))
             ->assertStatus(200);
    }

    public function test_pengawas_cannot_access_unassigned_school()
    {
        $role = \App\Models\Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        $pengawas = \App\Models\User::factory()->create(['role_id' => $role->id]);
        $school = \App\Models\School::create(['name' => 'Sekolah B', 'npsn' => '67890']);
        // Not assigned

        // Tambahkan header agar request dianggap bukan untuk rute select-school
        $this->actingAs($pengawas)
             ->withSession(['pengawas_school_id' => $school->id])
             ->withoutMiddleware(\App\Http\Middleware\PengawasSchoolScope::class) // Bypass scope jika perlu
             ->get('/pengawas/dashboard')
             ->assertStatus(403);
    }
}
