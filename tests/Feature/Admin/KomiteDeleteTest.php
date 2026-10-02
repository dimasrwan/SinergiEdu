<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tombol Hapus di kolom Aksi Manajemen Komite Sekolah:
 * - memakai confirmation modal custom halaman (pola Manajemen Guru);
 * - reuse route/controller destroy yang sudah ada (tanpa logic baru);
 * - feedback sukses konsisten dengan modul Admin lainnya.
 */
class KomiteDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected School $school;

    protected User $komite;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $roleKomite = Role::firstOrCreate(['name' => 'komite'], ['display_name' => 'Komite Sekolah']);

        $this->school = School::create([
            'npsn' => '9301',
            'name' => 'SMP Komite Test',
            'email' => 'komite.test@test.com',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'school_id' => $this->school->id,
            'name' => 'Admin Komite Test',
            'email' => 'admin.komite.test@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleAdmin->id,
            'is_active' => true,
        ]);

        $this->komite = User::create([
            'school_id' => $this->school->id,
            'name' => 'Komite Satu',
            'email' => 'komite.satu@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleKomite->id,
            'is_active' => true,
        ]);
    }

    public function test_index_renders_delete_button_and_confirmation_modal(): void
    {
        $html = $this->actingAs($this->admin)
            ->get(route('admin.komite.index'))
            ->assertOk()
            ->getContent();

        // Tombol Hapus sejajar Lihat + Edit (mobile card & tabel desktop),
        // icon trash konsisten dengan modul Admin lain.
        $this->assertStringContainsString('confirmDelete(', $html);
        $this->assertStringContainsString('title="Hapus"', $html);
        $this->assertStringContainsString('aria-label="Hapus komite"', $html);
        $this->assertStringContainsString('hover:text-red-600 hover:bg-red-50', $html);

        // Confirmation modal (pola Manajemen Guru) + form tersembunyi
        // yang mereuse route destroy yang sudah ada.
        $this->assertStringContainsString('modal-title-delete-komite', $html);
        $this->assertStringContainsString('id="delete-komite-form-' . $this->komite->id . '"', $html);
        $this->assertStringContainsString('/admin/komite/' . $this->komite->id, $html);
        $this->assertStringContainsString('name="_method" value="DELETE"', $html);

        // Tombol Lihat & Edit tetap ada.
        $this->assertStringContainsString('title="Lihat Detail"', $html);
        $this->assertStringContainsString('/admin/komite/' . $this->komite->id . '/edit', $html);
    }

    public function test_delete_uses_existing_route_with_consistent_success_feedback(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.komite.destroy', $this->komite))
            ->assertRedirect(route('admin.komite.index'))
            ->assertSessionHas('success', 'Akun Komite Sekolah berhasil dihapus.');

        $this->assertDatabaseMissing('users', ['id' => $this->komite->id]);
    }

    public function test_delete_guards_komite_from_other_school(): void
    {
        $roleKomite = Role::where('name', 'komite')->first();
        $otherSchool = School::create([
            'npsn' => '9302',
            'name' => 'SMP Lain',
            'email' => 'lain@test.com',
            'is_active' => true,
        ]);
        $komiteOther = User::create([
            'school_id' => $otherSchool->id,
            'name' => 'Komite Lain',
            'email' => 'komite.lain@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleKomite->id,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.komite.destroy', $komiteOther))
            ->assertNotFound();

        $this->assertDatabaseHas('users', ['id' => $komiteOther->id]);
    }
}
