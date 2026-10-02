<?php

declare(strict_types=1);

namespace Tests\Feature\WakaKurikulum;

use App\Models\AcademicYear;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Hapus Tahun Ajaran (Waka):
 * - tombol Hapus mobile + desktop mem-buka modal yang sama dan TERLIHAT
 *   (modal dibagikan di luar container `hidden lg:block`);
 * - route/controller destroy yang ada tetap dipakai, tanpa logic baru.
 */
class AcademicYearDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $waka;

    protected AcademicYear $year;

    protected function setUp(): void
    {
        parent::setUp();

        $school = School::create([
            'npsn' => '666666',
            'name' => 'SMP Waka Delete',
            'email' => 'waka.delete@test.com',
            'is_active' => true,
        ]);
        $wakaRole = Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka']);
        $this->waka = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $wakaRole->id,
        ]);
        $this->year = AcademicYear::create([
            'school_id' => $school->id,
            'year' => '2026/2027',
            'is_active' => false,
        ]);
    }

    public function test_delete_modal_is_shared_by_desktop_and_mobile_buttons(): void
    {
        $html = $this->actingAs($this->waka)
            ->get(route('waka.academic-years.index'))
            ->assertOk()
            ->getContent();

        $id = $this->year->id;

        // Tombol Hapus desktop (tabel) + mobile (card) memakai dispatch yang sama.
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($html, "'open-modal', 'delete-year-{$id}'")
        );

        // Modal Hapus dirender SETELAH section mobile card (di luar container
        // `hidden lg:block` desktop-only & `lg:hidden` mobile-only) →
        // terlihat pada kedua viewport.
        $modalPos = strpos($html, "\$event.detail == 'delete-year-{$id}'");
        $mobileSection = strpos($html, 'lg:hidden p-4 space-y-3');
        $this->assertNotFalse($modalPos);
        $this->assertNotFalse($mobileSection);
        $this->assertGreaterThan($mobileSection, $modalPos);

        // Layout responsif dipertahankan (desktop table + mobile card).
        $this->assertStringContainsString('hidden lg:block', $html);
        $this->assertStringContainsString('lg:hidden p-4 space-y-3', $html);

        // Isi modal: form DELETE ke route destroy yang sudah ada.
        $this->assertStringContainsString('/waka/academic-years/' . $id, $html);
        $this->assertStringContainsString('name="_method" value="DELETE"', $html);

        // Modal activate ikut terpindah (bug identik: dispatch mobile vs
        // modal di dalam container desktop-only).
        $activatePos = strpos($html, "\$event.detail == 'activate-year-{$id}'");
        $this->assertNotFalse($activatePos);
        $this->assertGreaterThan($mobileSection, $activatePos);
    }

    public function test_delete_year_uses_existing_route_with_success_feedback(): void
    {
        $this->actingAs($this->waka)
            ->delete(route('waka.academic-years.destroy', $this->year))
            ->assertRedirect(route('waka.academic-years.index'))
            ->assertSessionHas('success', 'Tahun ajaran berhasil dihapus.');

        $this->assertDatabaseMissing('academic_years', ['id' => $this->year->id]);
    }
}
