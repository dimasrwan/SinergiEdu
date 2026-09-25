<?php

namespace Tests\Feature\WakaKurikulum;

use App\Models\User;
use App\Models\Waka;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WakaAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_waka_can_access_dashboard()
    {
        $role = \App\Models\Role::firstOrCreate(['name' => 'waka', 'display_name' => 'Waka Kurikulum']);
        $school = \App\Models\School::create(['name' => 'S1', 'is_active' => true]);
        $user = User::factory()->create([
            'role_id' => $role->id,
            'school_id' => $school->id,
            'is_active' => true,
        ]);
        $waka = Waka::factory()->create(['user_id' => $user->id, 'school_id' => $school->id]);

        $response = $this->actingAs($user)->get(route('waka.dashboard'));
        $response->assertStatus(200);
    }
}
