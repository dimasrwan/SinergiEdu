<?php

declare(strict_types=1);

namespace Tests\Feature\WakaKurikulum;

use App\Models\AcademicYear;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Modal Manajemen Tahun Ajaran (Waka): overlay komponen x-modal memakai
 * backdrop-blur (background halaman blur + sedikit gelap), card modal tajam,
 * posisi/ukuran modal tidak berubah.
 */
class WakaAcademicYearModalBackdropTest extends TestCase
{
    use RefreshDatabase;

    protected User $waka;

    protected School $school;

    protected AcademicYear $year;

    protected function setUp(): void
    {
        parent::setUp();

        $school = School::create([
            'npsn' => '444444',
            'name' => 'SMP Waka Blur',
            'email' => 'waka.blur@test.com',
            'is_active' => true,
        ]);
        $this->school = $school;

        $wakaRole = Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka']);

        $this->waka = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $wakaRole->id,
        ]);

        $this->year = AcademicYear::create(['school_id' => $school->id, 'year' => '2025/2026', 'is_active' => true]);
        // Tahun nonaktif → modal "Ubah periode aktif" ikut ter-render (@if !$year->is_active).
        AcademicYear::create(['school_id' => $school->id, 'year' => '2026/2027', 'is_active' => false]);
    }

    public function test_both_modals_use_blurred_dim_overlay_and_keep_sharp_card(): void
    {
        $html = $this->actingAs($this->waka)
            ->get(route('waka.academic-years.index'))
            ->assertOk()
            ->getContent();

        // Overlay x-modal: backdrop blur + gelap (sumber sama untuk semua
        // modal berbasis x-modal → konsisten).
        $this->assertStringContainsString(
            'class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"',
            $html
        );

        // Center horizontal + vertical terhadap viewport (pola Admin),
        // bukan root top-anchored px-4 py-6 sm:px-0.
        $this->assertStringContainsString(
            'z-50 !mt-0 flex items-center justify-center p-4 sm:p-6',
            $html
        );
        $this->assertStringNotContainsString('overflow-y-auto px-4 py-6 sm:px-0', $html);

        // Tinggi dibatasi viewport → scroll di dalam dialog, tetap dalam viewport.
        $this->assertStringContainsString('max-h-[calc(100dvh-8rem)] overflow-y-auto', $html);

        // Modal z-50 tidak terkurung di bawah header z-40: .page-content-enter
        // tanpa will-change (stacking context) → overlay menutup seluruh halaman.
        $this->assertStringNotContainsString('will-change: opacity;', $html);

        // Kedua modal tetap ada, posisi/ukuran dialog tidak berubah
        // (maxWidth sm → sm:max-w-sm, card class tetap, tanpa blur di card).
        $this->assertStringContainsString('activate-year-', $html);
        $this->assertStringContainsString('delete-year-', $html);
        $this->assertStringContainsString('Ubah periode aktif?', $html);
        $this->assertStringContainsString('Konfirmasi Penghapusan', $html);
        $this->assertStringContainsString('sm:max-w-sm', $html);
        $this->assertStringContainsString('bg-surface border border-slate-100 rounded-2xl shadow-card', $html);

        // Card dialog TIDAK diblur (blur hanya di overlay di belakangnya).
        $this->assertDoesNotMatchRegularExpression(
            '/class="[^"]*backdrop-blur[^"]*"\s+x-transition[^>]*scale-95/',
            $html
        );
    }

    public function test_semester_action_modals_use_same_centered_blurred_pattern(): void
    {
        // Semester nonaktif → modal Set Aktif + Hapus sama-sama ter-render.
        Semester::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'name' => 'Ganjil',
            'is_active' => false,
        ]);

        $html = $this->actingAs($this->waka)
            ->get(route('waka.semesters.index'))
            ->assertOk()
            ->getContent();

        // Pola sama persis (sumber bersama x-modal): center viewport,
        // scroll dalam dialog, overlay gelap + backdrop blur.
        $this->assertStringContainsString(
            'z-50 !mt-0 flex items-center justify-center p-4 sm:p-6',
            $html
        );
        $this->assertStringContainsString('max-h-[calc(100dvh-8rem)] overflow-y-auto', $html);
        $this->assertStringNotContainsString('overflow-y-auto px-4 py-6 sm:px-0', $html);
        $this->assertStringContainsString(
            'class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"',
            $html
        );

        // Tombol aksi + logic tidak berubah.
        $this->assertStringContainsString('activate-semester-', $html);
        $this->assertStringContainsString('delete-semester-', $html);
        $this->assertStringContainsString('Set Aktif', $html);
        $this->assertStringContainsString('Konfirmasi Penghapusan', $html);
    }
}
