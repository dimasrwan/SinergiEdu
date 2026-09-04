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
        $user = User::factory()->create();
        $waka = Waka::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
    }
}
