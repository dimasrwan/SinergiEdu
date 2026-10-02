<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi layout tabel Manajemen Siswa (Daftar Siswa):
 * - header 6 kolom center;
 * - kolom Aksi nowrap (satu baris, tak turun) saat sidebar terbuka;
 * - container tabel tetap scroll horizontal (tidak ter-clip).
 */
class StudentIndexTableLayoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $school = School::create([
            'npsn' => '9201',
            'name' => 'SMP Siswa Layout',
            'email' => 'siswa.layout@test.com',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'school_id' => $school->id,
            'name' => 'Admin Siswa Layout',
            'email' => 'admin.siswa.layout@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleAdmin->id,
            'is_active' => true,
        ]);

        $siswa = User::create([
            'school_id' => $school->id,
            'name' => 'Siswa Satu',
            'email' => 'siswa.satu@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleSiswa->id,
            'is_active' => true,
        ]);

        $this->student = Student::create([
            'school_id' => $school->id,
            'user_id' => $siswa->id,
            'nis' => '1001',
            'gender' => 'L',
            'date_of_birth' => '2012-04-15',
        ]);
    }

    public function test_all_six_header_cells_are_centered(): void
    {
        $html = $this->actingAs($this->admin)
            ->get(route('admin.students.index'))
            ->assertOk()
            ->getContent();

        foreach (['Nama / NIS', 'Kelas Aktif', 'Jenis Kelamin', 'Tanggal Lahir', 'Orang Tua / Wali', 'Aksi'] as $header) {
            $this->assertMatchesRegularExpression(
                '/<th[^>]*text-center[^>]*>' . preg_quote($header, '/') . '<\/th>/',
                $html,
                "Header \"{$header}\" tidak memiliki text-center."
            );
        }
    }

    public function test_action_cell_is_nowrap_single_row_and_table_scrolls_inside_container(): void
    {
        $html = $this->actingAs($this->admin)
            ->get(route('admin.students.index'))
            ->assertOk()
            ->getContent();

        // Kolom Aksi: nowrap (td + baris tombol) → + Penempatan, Lihat, Edit,
        // Hapus tetap satu baris; tidak memakai flex-wrap yang membuat tombol turun.
        $this->assertStringContainsString('px-6 py-4 text-right whitespace-nowrap', $html);
        $this->assertStringContainsString('flex flex-nowrap items-center justify-end', $html);
        $this->assertStringNotContainsString('flex flex-wrap items-center justify-end', $html);

        // Ruang tidak cukup → scroll horizontal DI DALAM container tabel (bukan ter-clip),
        // floor min-w dipertahankan agar tabel tak mengerdil tak wajar.
        $this->assertStringContainsString('hidden lg:block overflow-x-auto', $html);
        $this->assertStringContainsString('min-w-[800px]', $html);

        // Isi kolom tetap utuh.
        $this->assertStringContainsString('Siswa Satu', $html);
        $this->assertStringContainsString('NIS: 1001', $html);
    }

    public function test_add_placement_modal_is_viewport_centered_with_inner_scroll(): void
    {
        $html = $this->actingAs($this->admin)
            ->get(route('admin.students.index'))
            ->assertOk()
            ->getContent();

        // Pola sama dengan "+ Penugasan" Manajemen Guru: center H+V terhadap
        // viewport (bukan x-modal top-anchored yang tenggelam di header).
        $this->assertStringContainsString('aria-label="Tambah Penempatan"', $html);
        $this->assertStringContainsString('z-50 !mt-0 flex items-center justify-center p-4 sm:p-6', $html);
        $this->assertStringContainsString('sm:max-w-xl', $html);

        // Tinggi dibatasi viewport → scroll di dalam dialog, tetap dalam viewport.
        $this->assertStringContainsString('max-h-[calc(100dvh-8rem)]', $html);

        // Overlay, form, field, route, logic penempatan dipertahankan.
        $this->assertStringContainsString('bg-slate-900/50', $html);
        $this->assertStringContainsString('student-placements', $html);
        $this->assertStringContainsString('name="student_ids[]"', $html);
        $this->assertStringContainsString('name="class_id"', $html);
        $this->assertStringContainsString('name="academic_year_id"', $html);
        $this->assertStringContainsString("\$dispatch('open-modal', 'add-placement-", $html);
        $this->assertStringContainsString("close-modal', 'add-placement-", $html);
        $this->assertStringContainsString('Simpan Penempatan', $html);

        // Modal Hapus siswa tidak diubah (tetap pakai x-modal).
        $this->assertStringContainsString('delete-student-', $html);
    }
}
