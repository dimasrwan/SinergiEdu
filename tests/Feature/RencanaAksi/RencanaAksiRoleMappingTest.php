<?php

namespace Tests\Feature\RencanaAksi;

use App\Models\Role;
use App\Models\School;
use App\Models\SchoolActionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Matriks Rencana Aksi:
 * pengawas -> kepala_sekolah; kepala_sekolah -> waka/guru; waka -> guru; guru -> siswa;
 * siswa -> read-only (hanya rencana miliknya).
 */
class RencanaAksiRoleMappingTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;
    private User $pengawas;
    private User $kepsek;
    private User $waka;
    private User $guru;
    private User $siswaA;
    private User $siswaB;
    private User $kepsekB;

    protected function setUp(): void
    {
        parent::setUp();

        $role = fn (string $name, string $label) => Role::firstOrCreate(
            ['name' => $name],
            ['display_name' => $label]
        );

        $rolePengawas = $role('pengawas', 'Pengawas Sekolah');
        $roleKepsek = $role('kepala_sekolah', 'Kepala Sekolah');
        $roleWaka = $role('waka', 'Waka Kurikulum');
        $roleGuru = $role('guru', 'Guru');
        $roleSiswa = $role('siswa', 'Siswa');

        $this->schoolA = School::create([
            'npsn' => '55555551',
            'name' => 'Sekolah A',
            'email' => 'ra.sekolaha@test.com',
            'is_active' => true,
        ]);

        $this->schoolB = School::create([
            'npsn' => '55555552',
            'name' => 'Sekolah B',
            'email' => 'ra.sekolahb@test.com',
            'is_active' => true,
        ]);

        $make = fn (Role $r, School $school, string $name, string $email) => User::create([
            'role_id' => $r->id,
            'school_id' => $school->id,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->pengawas = $make($rolePengawas, $this->schoolA, 'Pengawas Nana', 'ra.pengawas@test.com');
        $this->kepsek = $make($roleKepsek, $this->schoolA, 'Kepala Andi', 'ra.kepsek@test.com');
        $this->waka = $make($roleWaka, $this->schoolA, 'Waka Budi', 'ra.waka@test.com');
        $this->guru = $make($roleGuru, $this->schoolA, 'Guru Citra', 'ra.guru@test.com');
        $this->siswaA = $make($roleSiswa, $this->schoolA, 'Siswa Dewi', 'ra.siswa.a@test.com');
        $this->siswaB = $make($roleSiswa, $this->schoolA, 'Siswa Raka', 'ra.siswa.b@test.com');
        $this->kepsekB = $make($roleKepsek, $this->schoolB, 'Kepala Eka', 'ra.kepsek.b@test.com');

        $this->pengawas->assignedSchools()->attach($this->schoolA->id);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Tindak lanjut evaluasi pembelajaran',
            'description' => 'Langkah perbaikan hasil evaluasi.',
            'category' => 'academic',
            'priority' => 'high',
            'target_role' => '',
            'target_user_id' => '',
        ], $overrides);
    }

    private function pengawasRequest(array $extraSession = [])
    {
        return $this->actingAs($this->pengawas)
            ->withSession(array_merge(['pengawas_school_id' => $this->schoolA->id], $extraSession));
    }

    /**
     * Daftar opsi komponen x-select dari state Alpine (options pada x-data).
     *
     * @return array<int, array{value: string, label: string}>
     */
    private function selectOptions(string $html, string $id): array
    {
        $pos = strpos($html, 'id="'.$id.'"');
        $this->assertNotFalse($pos, "Select {$id} tidak dirender.");
        $start = strrpos(substr($html, 0, $pos), 'x-data="{');
        $this->assertNotFalse($start, "x-data select {$id} tidak ditemukan.");
        $block = substr($html, $start, $pos - $start);

        if (preg_match("/options: JSON\.parse\('((?:\\\\.|[^'])*)'\)/", $block, $m)) {
            $decoded = json_decode(str_replace('\u0022', '"', $m[1]), true);
        } elseif (preg_match('/options: (\[.*?\])\s*,/', $block, $m)) {
            $decoded = json_decode($m[1], true);
        } else {
            $this->fail("Opsi select {$id} tidak ditemukan.");
        }

        $this->assertIsArray($decoded, "Opsi select {$id} bukan JSON valid.");

        return $decoded;
    }

    private function assertNoPlansStored(string $because): void
    {
        $this->assertSame(
            0,
            \Illuminate\Support\Facades\DB::table('school_action_plans')->count(),
            $because
        );
    }

    // ------------------------------------------------------------------
    // UI Pengawas: menu terpisah dari Feedback + tombol Buat
    // ------------------------------------------------------------------

    public function test_pengawas_sidebar_has_separate_rencana_aksi_menu_and_index_has_create_button(): void
    {
        // Halaman Feedback: sidebar menampilkan link menuju Rencana Aksi (terpisah).
        $feedback = $this->pengawasRequest()->get(route('pengawas.feedback.index'));
        $feedback->assertOk();
        $feedback->assertSee(route('pengawas.rencana-aksi.index'), false);
        $feedback->assertSee('Rencana Aksi');

        // Halaman index Rencana Aksi: tombol "+ Buat Rencana Aksi" menuju route create.
        $index = $this->pengawasRequest()->get(route('pengawas.rencana-aksi.index'));
        $index->assertOk();
        $index->assertSee('Buat Rencana Aksi');
        $index->assertSee(route('pengawas.rencana-aksi.create'), false);

        // Halaman create: hanya target Kepala Sekolah.
        $create = $this->pengawasRequest()->get(route('pengawas.rencana-aksi.create'));
        $create->assertOk();
        $roleValues = array_column($this->selectOptions($create->getContent(), 'target_role'), 'value');
        $this->assertSame(['', 'kepala_sekolah'], $roleValues);
    }

    // ------------------------------------------------------------------
    // Pengawas -> Kepala Sekolah
    // ------------------------------------------------------------------

    public function test_pengawas_create_page_only_offers_kepala_sekolah_target(): void
    {
        $response = $this->pengawasRequest()->get(route('pengawas.rencana-aksi.create'));

        $response->assertOk();
        $html = $response->getContent();

        $roleValues = array_column($this->selectOptions($html, 'target_role'), 'value');
        $this->assertContains('kepala_sekolah', $roleValues);
        $this->assertNotContains('guru', $roleValues);
        $this->assertNotContains('waka', $roleValues);
        $this->assertNotContains('siswa', $roleValues);

        // Target Role belum dipilih → Target Orang hanya placeholder (tidak menampilkan semua user).
        $personLabels = array_column($this->selectOptions($html, 'target_user_id'), 'label');
        $this->assertSame(['Pilih Target Role terlebih dahulu'], $personLabels);
        $response->assertSee('KS_ACTION_PLAN_FILTER', false);

        // Target Role dipilih → Target Orang = user role tsb HANYA dari sekolah context aktif.
        $afterRole = $this->pengawasRequest(['_old_input' => ['target_role' => 'kepala_sekolah']])
            ->get(route('pengawas.rencana-aksi.create'));
        $afterRole->assertOk();
        $personLabels = array_column($this->selectOptions($afterRole->getContent(), 'target_user_id'), 'label');
        $this->assertContains('Kepala Andi', $personLabels);
        $this->assertNotContains('Guru Citra', $personLabels);
        $this->assertNotContains('Siswa Dewi', $personLabels);
    }

    public function test_pengawas_target_person_list_follows_active_school_context(): void
    {
        // Pengawas mengawasi dua sekolah; tiap sekolah punya kepala sekolah sendiri.
        $this->pengawas->assignedSchools()->attach($this->schoolB->id);

        // Context School A → hanya Kepala Sekolah School A.
        $labelsA = array_column($this->selectOptions(
            $this->pengawasRequest([
                '_old_input' => ['target_role' => 'kepala_sekolah'],
            ])->get(route('pengawas.rencana-aksi.create'))->getContent(),
            'target_user_id'
        ), 'label');
        $this->assertContains('Kepala Andi', $labelsA);
        $this->assertNotContains('Kepala Eka', $labelsA);

        // Context berpindah ke School B → daftar mengikuti sekolah baru.
        $labelsB = array_column($this->selectOptions(
            $this->actingAs($this->pengawas)->withSession([
                'pengawas_school_id' => $this->schoolB->id,
                '_old_input' => ['target_role' => 'kepala_sekolah'],
            ])->get(route('pengawas.rencana-aksi.create'))->getContent(),
            'target_user_id'
        ), 'label');
        $this->assertContains('Kepala Eka', $labelsB);
        $this->assertNotContains('Kepala Andi', $labelsB);
    }

    public function test_pengawas_rejects_target_person_from_non_active_school(): void
    {
        // Context aktif School A, target user dari School B → ditolak backend.
        $response = $this->pengawasRequest()->post(route('pengawas.rencana-aksi.store'), $this->payload([
            'target_role' => 'kepala_sekolah',
            'target_user_id' => $this->kepsekB->id,
        ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertNoPlansStored('Target user sekolah lain harus ditolak.');
    }

    public function test_pengawas_with_unassigned_school_context_is_redirected_and_cannot_store(): void
    {
        // Context session menunjuk sekolah yang TIDAK sedang dipilih/assigned →
        // PengawasSchoolScope redirect; request tidak pernah sampai controller.
        $response = $this->actingAs($this->pengawas)->withSession([
            'pengawas_school_id' => $this->schoolB->id, // tidak di-attach
        ])->post(route('pengawas.rencana-aksi.store'), $this->payload([
            'target_role' => 'kepala_sekolah',
            'target_user_id' => $this->kepsekB->id,
        ]));

        $response->assertRedirect(route('pengawas.select-school'));
        $this->assertNoPlansStored('Context sekolah tak-aktif/tak-terassigned tidak boleh menyimpan.');
    }

    public function test_stale_target_person_is_rejected_and_reset_after_target_role_change(): void
    {
        // Simulasi: target_user_id lama (waka) masih terkirim saat Target Role
        // sudah diganti ke guru → backend menolak.
        $response = $this->actingAs($this->kepsek)->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
            'target_role' => 'guru',
            'target_user_id' => $this->waka->id,
        ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertNoPlansStored('Target user lama yang tak sesuai Target Role baru harus ditolak.');

        // Rerender form: Target Role lama=guru → waka tidak ada di daftar & pilihan direset.
        $html = $this->actingAs($this->kepsek)->withSession([
            '_old_input' => ['target_role' => 'guru', 'target_user_id' => (string) $this->waka->id],
        ])->get(route('kepala-sekolah.rencana-aksi.create'))->getContent();

        $personLabels = array_column($this->selectOptions($html, 'target_user_id'), 'label');
        $this->assertContains('Guru Citra', $personLabels);
        $this->assertNotContains('Waka Budi', $personLabels);

        $pos = strpos($html, 'id="target_user_id"');
        $start = strrpos(substr($html, 0, $pos), 'x-data="{');
        $block = substr($html, $start, $pos - $start);
        $this->assertMatchesRegularExpression("/selectedVal: ''/", $block);
    }

    public function test_pengawas_can_store_targeting_kepala_sekolah(): void
    {
        $response = $this->pengawasRequest()->post(route('pengawas.rencana-aksi.store'), $this->payload([
            'target_role' => 'kepala_sekolah',
            'target_user_id' => $this->kepsek->id,
        ]));

        $response->assertRedirect(route('pengawas.rencana-aksi.index'));
        $this->assertDatabaseHas('school_action_plans', [
            'user_id' => $this->pengawas->id,
            'target_role' => 'kepala_sekolah',
            'target_user_id' => $this->kepsek->id,
            'title' => 'Tindak lanjut evaluasi pembelajaran',
        ]);

        // Rencana yang dibuat pengawas terlihat di index-nya.
        $this->pengawasRequest()->get(route('pengawas.rencana-aksi.index'))
            ->assertOk()
            ->assertSee('Tindak lanjut evaluasi pembelajaran');
    }

    public function test_pengawas_rejects_guru_waka_siswa_target_role(): void
    {
        foreach (['guru', 'waka', 'siswa'] as $targetRole) {
            $response = $this->pengawasRequest()->post(route('pengawas.rencana-aksi.store'), $this->payload([
                'target_role' => $targetRole,
            ]));

            $response->assertSessionHasErrors('target_role');
            $this->assertNoPlansStored("target_role={$targetRole} tidak boleh diterima pengawas.");
        }
    }

    public function test_pengawas_rejects_target_person_outside_mapping(): void
    {
        $response = $this->pengawasRequest()->post(route('pengawas.rencana-aksi.store'), $this->payload([
            'target_role' => '',
            'target_user_id' => $this->guru->id,
        ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    // ------------------------------------------------------------------
    // Kepala Sekolah -> Waka/Guru (pengawas & siswa ditolak)
    // ------------------------------------------------------------------

    public function test_kepsek_rejects_pengawas_target_role(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => 'pengawas',
            ]));

        $response->assertSessionHasErrors('target_role');
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    public function test_kepsek_rejects_pengawas_target_person(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => '',
                'target_user_id' => $this->pengawas->id,
            ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    public function test_kepsek_rejects_siswa_target_role(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => 'siswa',
            ]));

        $response->assertSessionHasErrors('target_role');
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    // ------------------------------------------------------------------
    // Waka -> Guru
    // ------------------------------------------------------------------

    public function test_waka_create_page_only_offers_guru_target(): void
    {
        $response = $this->actingAs($this->waka)->get(route('waka.rencana-aksi.create'));

        $response->assertOk();
        $html = $response->getContent();

        $roleValues = array_column($this->selectOptions($html, 'target_role'), 'value');
        $this->assertContains('guru', $roleValues);
        $this->assertNotContains('kepala_sekolah', $roleValues);
        $this->assertNotContains('siswa', $roleValues);

        // Target Role belum dipilih → Target Orang hanya placeholder.
        $personLabels = array_column($this->selectOptions($html, 'target_user_id'), 'label');
        $this->assertSame(['Pilih Target Role terlebih dahulu'], $personLabels);

        // Target Role = guru → hanya Guru dari sekolah Waka.
        $afterRole = $this->actingAs($this->waka)->withSession(['_old_input' => ['target_role' => 'guru']])
            ->get(route('waka.rencana-aksi.create'));
        $afterRole->assertOk();
        $personLabels = array_column($this->selectOptions($afterRole->getContent(), 'target_user_id'), 'label');
        $this->assertContains('Guru Citra', $personLabels);
        $this->assertNotContains('Kepala Andi', $personLabels);
    }

    public function test_waka_can_store_targeting_guru(): void
    {
        $response = $this->actingAs($this->waka)->post(route('waka.rencana-aksi.store'), $this->payload([
            'target_role' => 'guru',
            'target_user_id' => $this->guru->id,
        ]));

        $response->assertRedirect(route('waka.rencana-aksi.index'));
        $this->assertDatabaseHas('school_action_plans', [
            'user_id' => $this->waka->id,
            'target_role' => 'guru',
            'target_user_id' => $this->guru->id,
        ]);
    }

    public function test_waka_rejects_kepala_sekolah_and_siswa_targets(): void
    {
        foreach (['kepala_sekolah', 'siswa'] as $targetRole) {
            $response = $this->actingAs($this->waka)->post(route('waka.rencana-aksi.store'), $this->payload([
                'target_role' => $targetRole,
            ]));

            $response->assertSessionHasErrors('target_role');
            $this->assertNoPlansStored("target_role={$targetRole} tidak boleh diterima waka.");
        }

        // Lewat target orang (target_role kosong) juga ditolak.
        $response = $this->actingAs($this->waka)->post(route('waka.rencana-aksi.store'), $this->payload([
            'target_role' => '',
            'target_user_id' => $this->kepsek->id,
        ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertNoPlansStored('Target orang di luar mapping tidak boleh disimpan waka.');
    }

    // ------------------------------------------------------------------
    // Guru -> Siswa
    // ------------------------------------------------------------------

    public function test_guru_create_page_only_offers_siswa_target(): void
    {
        $response = $this->actingAs($this->guru)->get(route('guru.rencana-aksi.create'));

        $response->assertOk();
        $html = $response->getContent();

        $roleValues = array_column($this->selectOptions($html, 'target_role'), 'value');
        $this->assertContains('siswa', $roleValues);
        $this->assertNotContains('guru', $roleValues);
        $this->assertNotContains('waka', $roleValues);
        $this->assertNotContains('kepala_sekolah', $roleValues);

        // Target Role belum dipilih → Target Orang hanya placeholder.
        $personLabels = array_column($this->selectOptions($html, 'target_user_id'), 'label');
        $this->assertSame(['Pilih Target Role terlebih dahulu'], $personLabels);

        // Target Role = siswa → hanya Siswa dari sekolah Guru.
        $afterRole = $this->actingAs($this->guru)->withSession(['_old_input' => ['target_role' => 'siswa']])
            ->get(route('guru.rencana-aksi.create'));
        $afterRole->assertOk();
        $personLabels = array_column($this->selectOptions($afterRole->getContent(), 'target_user_id'), 'label');
        $this->assertContains('Siswa Dewi', $personLabels);
        $this->assertContains('Siswa Raka', $personLabels);
        $this->assertNotContains('Guru Citra', $personLabels);
    }

    public function test_guru_can_store_targeting_specific_siswa(): void
    {
        $response = $this->actingAs($this->guru)->post(route('guru.rencana-aksi.store'), $this->payload([
            'target_role' => 'siswa',
            'target_user_id' => $this->siswaA->id,
        ]));

        $response->assertRedirect(route('guru.rencana-aksi.index'));
        $this->assertDatabaseHas('school_action_plans', [
            'user_id' => $this->guru->id,
            'target_role' => 'siswa',
            'target_user_id' => $this->siswaA->id,
        ]);
    }

    public function test_guru_rejects_non_siswa_targets(): void
    {
        foreach (['guru', 'waka', 'kepala_sekolah'] as $targetRole) {
            $response = $this->actingAs($this->guru)->post(route('guru.rencana-aksi.store'), $this->payload([
                'target_role' => $targetRole,
            ]));

            $response->assertSessionHasErrors('target_role');
            $this->assertNoPlansStored("target_role={$targetRole} tidak boleh diterima guru.");
        }

        $response = $this->actingAs($this->guru)->post(route('guru.rencana-aksi.store'), $this->payload([
            'target_role' => '',
            'target_user_id' => $this->waka->id,
        ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertNoPlansStored('Target orang di luar mapping tidak boleh disimpan guru.');
    }

    public function test_store_requires_target_role_or_target_person(): void
    {
        $response = $this->actingAs($this->guru)->post(route('guru.rencana-aksi.store'), $this->payload([
            'target_role' => '',
            'target_user_id' => '',
        ]));

        $response->assertSessionHasErrors(['target_role', 'target_user_id']);
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    // ------------------------------------------------------------------
    // Siswa: read-only
    // ------------------------------------------------------------------

    public function test_siswa_has_no_create_or_store_route(): void
    {
        $this->assertFalse(Route::has('siswa.rencana-aksi.create'));
        $this->assertFalse(Route::has('siswa.rencana-aksi.store'));

        $this->actingAs($this->siswaA)->get('/siswa/rencana-aksi/create')->assertNotFound();
        // POST ke URI index (GET-only) → 405 Method Not Allowed: route store memang tidak ada.
        $this->actingAs($this->siswaA)->post('/siswa/rencana-aksi', $this->payload())->assertStatus(405);
        $this->assertNoPlansStored('Siswa tidak boleh menyimpan rencana aksi.');
    }

    public function test_siswa_sees_only_plans_addressed_to_them(): void
    {
        $makePlan = fn (array $overrides) => SchoolActionPlan::create(array_merge([
            'school_id' => $this->schoolA->id,
            'user_id' => $this->guru->id,
            'title' => 'Rencana wajib isi judul',
            'category' => 'academic',
            'priority' => 'high',
            'status' => 'draft',
        ], $overrides));

        $makePlan(['title' => 'Perbaikan nilai untuk Dewi', 'target_role' => 'siswa', 'target_user_id' => $this->siswaA->id]);
        $makePlan(['title' => 'Perbaikan nilai untuk Raka', 'target_role' => 'siswa', 'target_user_id' => $this->siswaB->id]);
        $makePlan(['title' => 'Pengumuman untuk semua siswa', 'target_role' => 'siswa', 'target_user_id' => null]);

        $response = $this->actingAs($this->siswaA)->get(route('siswa.rencana-aksi.index'));

        $response->assertOk();
        $response->assertSee('Perbaikan nilai untuk Dewi');
        $response->assertSee('Pengumuman untuk semua siswa');
        $response->assertDontSee('Perbaikan nilai untuk Raka');
    }

    public function test_siswa_cannot_open_other_students_plan(): void
    {
        $own = SchoolActionPlan::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $this->guru->id,
            'title' => 'Rencana milik Dewi',
            'target_role' => 'siswa',
            'target_user_id' => $this->siswaA->id,
            'category' => 'academic',
            'priority' => 'high',
            'status' => 'draft',
        ]);

        $other = SchoolActionPlan::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $this->guru->id,
            'title' => 'Rencana milik Raka',
            'target_role' => 'siswa',
            'target_user_id' => $this->siswaB->id,
            'category' => 'academic',
            'priority' => 'high',
            'status' => 'draft',
        ]);

        $this->actingAs($this->siswaA)
            ->get(route('siswa.rencana-aksi.show', $own))
            ->assertOk();

        $this->actingAs($this->siswaA)
            ->get(route('siswa.rencana-aksi.show', $other))
            ->assertNotFound();
    }

    public function test_guru_cannot_open_plan_addressed_to_other_role(): void
    {
        $plan = SchoolActionPlan::create([
            'school_id' => $this->schoolA->id,
            'user_id' => $this->kepsek->id,
            'title' => 'Rencana internal kepala sekolah',
            'target_role' => 'waka',
            'target_user_id' => $this->waka->id,
            'category' => 'academic',
            'priority' => 'high',
            'status' => 'draft',
        ]);

        $this->actingAs($this->guru)
            ->get(route('guru.rencana-aksi.show', $plan))
            ->assertNotFound();

        $this->actingAs($this->waka)
            ->get(route('waka.rencana-aksi.show', $plan))
            ->assertOk();
    }

    // ------------------------------------------------------------------
    // Tenant isolation
    // ------------------------------------------------------------------

    public function test_other_school_plans_are_invisible(): void
    {
        $planB = SchoolActionPlan::create([
            'school_id' => $this->schoolB->id,
            'user_id' => $this->kepsekB->id,
            'title' => 'Rencana sekolah lain',
            'target_role' => 'siswa',
            'target_user_id' => null,
            'category' => 'academic',
            'priority' => 'high',
            'status' => 'draft',
        ]);

        $this->actingAs($this->siswaA)->get(route('siswa.rencana-aksi.index'))
            ->assertOk()
            ->assertDontSee('Rencana sekolah lain');

        $this->actingAs($this->siswaA)->get(route('siswa.rencana-aksi.show', $planB))
            ->assertNotFound();

        // User sekolah lain tidak valid jadi target (validasi backend).
        $response = $this->actingAs($this->guru)->post(route('guru.rencana-aksi.store'), $this->payload([
            'target_role' => 'siswa',
            'target_user_id' => $this->kepsekB->id,
        ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertDatabaseCount('school_action_plans', 1);
    }

    // ------------------------------------------------------------------
    // Route menu: hanya 5 role terkait
    // ------------------------------------------------------------------

    public function test_only_mapped_roles_have_rencana_aksi_routes(): void
    {
        foreach (['pengawas', 'kepala-sekolah', 'waka', 'guru', 'siswa'] as $prefix) {
            $this->assertTrue(Route::has("{$prefix}.rencana-aksi.index"), "Route {$prefix}.rencana-aksi.index harus ada.");
        }

        foreach (['komite', 'orangtua', 'admin', 'super_admin'] as $prefix) {
            $this->assertFalse(Route::has("{$prefix}.rencana-aksi.index"), "Route {$prefix}.rencana-aksi.index tidak boleh ada.");
        }
    }
}
