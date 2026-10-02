<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi layout tabel Manajemen Guru (Daftar Guru):
 * - header 4 kolom center;
 * - container tabel tetap scroll (tidak pernah ter-clip) saat sidebar terbuka;
 * - lebar kolom Kelas & Mapel tidak kaku (tanpa min-w-[260px]).
 */
class TeacherIndexTableLayoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);

        $school = School::create([
            'npsn' => '9101',
            'name' => 'SMP Layout',
            'email' => 'layout@test.com',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'school_id' => $school->id,
            'name' => 'Admin Layout',
            'email' => 'admin.layout@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleAdmin->id,
            'is_active' => true,
        ]);

        $academicYear = AcademicYear::create([
            'year' => '2025/2026',
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        $semester = Semester::create([
            'name' => 'Ganjil',
            'school_id' => $school->id,
            'academic_year_id' => $academicYear->id,
            'is_active' => true,
        ]);

        $subject = Subject::create(['name' => 'Matematika', 'code' => 'MTK', 'school_id' => $school->id]);
        $classroom = Classroom::create([
            'name' => 'Kelas VII-A',
            'grade_level' => 7,
            'school_id' => $school->id,
            'academic_year_id' => $academicYear->id,
        ]);

        $guruRole = Role::where('name', 'guru')->first();
        $guru = User::create([
            'school_id' => $school->id,
            'name' => 'Guru Satu',
            'email' => 'guru.satu@test.com',
            'password' => bcrypt('password'),
            'role_id' => $guruRole->id,
            'is_active' => true,
        ]);

        $teacher = Teacher::create([
            'school_id' => $school->id,
            'user_id' => $guru->id,
            'nip' => '197903142005011001',
            'phone' => '081234567890',
        ]);

        TeacherSubject::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'class_id' => $classroom->id,
            'academic_year_id' => $academicYear->id,
            'semester_id' => $semester->id,
        ]);
    }

    private function renderIndex(): string
    {
        return $this->actingAs($this->admin)
            ->get(route('admin.teachers.index'))
            ->assertOk()
            ->getContent();
    }

    public function test_all_four_header_cells_are_centered(): void
    {
        $html = $this->renderIndex();

        foreach (['Nama / NIP', 'Kontak', 'Kelas & Mapel', 'Aksi'] as $header) {
            $this->assertMatchesRegularExpression(
                '/<th[^>]*text-center[^>]*>' . preg_quote($header, '/') . '<\/th>/',
                $html,
                "Header \"{$header}\" tidak memiliki text-center."
            );
        }

        // Header Aksi tidak boleh kembali rata kanan.
        $this->assertDoesNotMatchRegularExpression(
            '/<th[^>]*text-right[^>]*>Aksi<\/th>/',
            $html
        );
    }

    public function test_table_container_keeps_horizontal_scroll_and_no_rigid_min_width(): void
    {
        $html = $this->renderIndex();

        // Container tabel wajib scroll horizontal di semua breakpoint
        // (sebelumnya lg:overflow-visible → konten ter-clip di .main-column-pane
        //  yang overflow-x-hidden saat sidebar terbuka menyempitkan area konten).
        $this->assertStringContainsString('overflow-x-auto', $html);
        $this->assertStringNotContainsString('lg:overflow-visible', $html);

        // Lebar kolom Kelas & Mapel mengikuti ruang konten, bukan dipaksa 260px.
        $this->assertStringNotContainsString('min-w-[260px]', $html);

        // Struktur data & isi kolom tetap utuh.
        $this->assertStringContainsString('Guru Satu', $html);
        $this->assertStringContainsString('197903142005011001', $html);
        $this->assertStringContainsString('Matematika', $html);
    }

    public function test_add_assignment_modal_is_viewport_centered_with_inner_scroll(): void
    {
        $html = $this->renderIndex();

        // Panel modal di-center horizontal + vertikal terhadap viewport
        // (sebelumnya x-modal root top-anchored: panel menempel di atas,
        //  puncaknya tertimpa header sticky z-40).
        $this->assertStringContainsString('flex items-center justify-center', $html);
        $this->assertStringContainsString('sm:max-w-xl', $html);

        // Tinggi panel dibatasi viewport → tidak pernah keluar viewport,
        // konten kepanjangan scroll di dalam dialog sendiri.
        $this->assertStringContainsString('max-h-[calc(100dvh-8rem)]', $html);
        $this->assertStringContainsString('overflow-y-auto', $html);

        // Struktur x-modal lama (top-anchored, py-6) tidak dipakai lagi.
        $this->assertStringNotContainsString('overflow-y-auto px-4 py-6', $html);

        // Fitur + Penugasan dipertahankan: dispatch event, form, route, field.
        $this->assertStringContainsString("\$dispatch('open-modal', 'add-assignment-", $html);
        $this->assertStringContainsString("close-modal', 'add-assignment-", $html);
        $this->assertStringContainsString('/admin/teacher-assignments"', $html);
        $this->assertStringContainsString('name="subject_id"', $html);
        $this->assertStringContainsString('name="class_id"', $html);
        $this->assertStringContainsString('name="semester_id"', $html);

        // Modal Hapus tidak berubah.
        $this->assertStringContainsString('modal-title-delete-teacher', $html);
    }
}
