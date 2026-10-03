<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup permissions if any are required, but for string roles we are fine.
    }

    public function test_kepala_sekolah_can_download_weekly_pdf()
    {
        $school = School::create(['name' => 'School A']);
        $kepsekRole = \App\Models\Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
        $kepsek = User::factory()->create(['school_id' => $school->id, 'role_id' => $kepsekRole->id]);

        $response = $this->actingAs($kepsek)->get(route('kepala-sekolah.reports.export-weekly-pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_kepala_sekolah_can_download_monthly_pdf()
    {
        $school = School::create(['name' => 'School A']);
        $kepsekRole = \App\Models\Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
        $kepsek = User::factory()->create(['school_id' => $school->id, 'role_id' => $kepsekRole->id]);

        $response = $this->actingAs($kepsek)->get(route('kepala-sekolah.reports.export-monthly-pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_guest_cannot_download_pdf()
    {
        $response = $this->get(route('kepala-sekolah.reports.export-weekly-pdf'));
        $response->assertRedirect('/login');
    }

    public function test_wrong_role_cannot_download_pdf()
    {
        $school = School::create(['name' => 'School A']);
        $siswaRole = \App\Models\Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $student = User::factory()->create(['school_id' => $school->id, 'role_id' => $siswaRole->id]);

        $response = $this->actingAs($student)->get(route('kepala-sekolah.reports.export-weekly-pdf'));
        $response->assertStatus(403);
    }
}
