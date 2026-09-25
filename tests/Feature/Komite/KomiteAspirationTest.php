<?php

namespace Tests\Feature\Komite;

use App\Models\KomiteAspiration;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KomiteAspirationTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;
    protected User $komiteUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'komite'], ['display_name' => 'Komite Sekolah']);

        $this->school = School::create([
            'npsn' => '12345678',
            'name' => 'SMA Aspiration Test',
            'email' => 'aspiration@test.com',
            'is_active' => true,
        ]);

        $this->komiteUser = User::create([
            'name' => 'Komite User',
            'email' => 'komiteasp@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);
    }

    public function test_komite_can_create_aspiration_and_saves_with_correct_tenant()
    {
        $response = $this->actingAs($this->komiteUser)->post('/komite/aspirations', [
            'title' => 'Usulan Peningkatan Perpustakaan',
            'content' => 'Pengadaan buku-buku digital baru untuk siswa.',
        ]);

        $response->assertRedirect('/komite/aspirations');

        $this->assertDatabaseHas('komite_aspirations', [
            'school_id' => $this->school->id,
            'user_id' => $this->komiteUser->id,
            'title' => 'Usulan Peningkatan Perpustakaan',
            'status' => 'pending',
        ]);
    }
}
