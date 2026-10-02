<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Role;
use App\Models\School;
use App\Models\SchoolActionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActionPlanTargetTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;
    private User $kepsek;
    private User $guruA;
    private User $wakaA;
    private User $siswaA;
    private User $guruB;

    protected function setUp(): void
    {
        parent::setUp();

        $roleKepsek = Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
        $roleGuru = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        $roleWaka = Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka Kurikulum']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $this->schoolA = School::create([
            'npsn' => '33333333',
            'name' => 'Sekolah A',
            'email' => 'sekolaha@test.com',
            'is_active' => true,
        ]);

        $this->schoolB = School::create([
            'npsn' => '44444444',
            'name' => 'Sekolah B',
            'email' => 'sekolahb@test.com',
            'is_active' => true,
        ]);

        $make = fn (Role $role, School $school, string $name, string $email) => User::create([
            'role_id' => $role->id,
            'school_id' => $school->id,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->kepsek = $make($roleKepsek, $this->schoolA, 'Kepala A', 'kepsek.a@test.com');
        $this->guruA = $make($roleGuru, $this->schoolA, 'Guru Andi A', 'guru.a@test.com');
        $this->wakaA = $make($roleWaka, $this->schoolA, 'Waka Budi A', 'waka.a@test.com');
        $this->siswaA = $make($roleSiswa, $this->schoolA, 'Siswa Dewi A', 'siswa.a@test.com');
        $this->guruB = $make($roleGuru, $this->schoolB, 'Guru Eka B', 'guru.b@test.com');
    }

    /**
     * Decode JSON daftar target (window.KS_ACTION_PLAN_TARGETS) dari HTML render.
     *
     * @return array<int, array{value: string, label: string, role: ?string}>
     */
    private function targetOptions(string $html): array
    {
        $this->assertStringContainsString('window.KS_ACTION_PLAN_TARGETS', $html, 'Payload daftar target tidak dirender.');
        preg_match('/window\.KS_ACTION_PLAN_TARGETS = (.*);/', $html, $m);
        $this->assertNotEmpty($m, 'Assignment KS_ACTION_PLAN_TARGETS tidak ditemukan.');

        $raw = trim($m[1]);
        if (preg_match("/^JSON\\.parse\\('(.*)'\\)$/", $raw, $inner)) {
            $raw = $inner[1];
        }

        $decoded = json_decode(str_replace('\u0022', '"', $raw), true);
        $this->assertIsArray($decoded, 'Payload KS_ACTION_PLAN_TARGETS bukan JSON valid.');

        return $decoded;
    }

    /**
     * Baca state Alpine select Target Orang (selectedVal + options) dari HTML render.
     *
     * @return array{value: ?string, options: array<int, array{value: string, label: string}>}
     */
    private function targetSelectState(string $html): array
    {
        $pos = strpos($html, 'id="target_user_id"');
        $this->assertNotFalse($pos, 'Select Target Orang tidak dirender.');
        $start = strrpos(substr($html, 0, $pos), 'x-data="{');
        $this->assertNotFalse($start, 'x-data select Target Orang tidak ditemukan.');
        $block = substr($html, $start, $pos - $start);

        preg_match("/selectedVal: '((?:\\\\.|[^'])*)'/", $block, $value);
        preg_match("/options: JSON\.parse\('((?:\\\\.|[^'])*)'\)/", $block, $opts);

        $options = [];
        if (isset($opts[1])) {
            $decoded = json_decode(str_replace('\u0022', '"', $opts[1]), true);
            $this->assertIsArray($decoded, 'Options select Target Orang bukan JSON valid.');
            $options = $decoded;
        }

        return ['value' => $value[1] ?? null, 'options' => $options];
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'target_role' => 'guru',
            'target_user_id' => $this->guruA->id,
            'title' => 'Evaluasi pembelajaran semester ini',
            'category' => 'academic',
            'priority' => 'high',
        ], $overrides);
    }

    public function test_create_keeps_target_person_placeholder_when_target_role_empty(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.rencana-aksi.create'));

        $response->assertOk();
        $html = $response->getContent();

        // Target Role kosong → Target Orang hanya placeholder (TIDAK menampilkan semua user).
        $state = $this->targetSelectState($html);
        $labels = array_column($state['options'], 'label');
        $this->assertSame(['Pilih Target Role terlebih dahulu'], $labels);

        // Payload filter dinamis (KS_ACTION_PLAN_TARGETS) tetap dibawa untuk saat role
        // dipilih, dan tetap tenant-isolated (user sekolah lain tidak pernah ikut).
        $payloadLabels = array_column($this->targetOptions($html), 'label');
        $this->assertContains('Guru Andi A', $payloadLabels);
        $this->assertContains('Waka Budi A', $payloadLabels);
        $this->assertNotContains('Guru Eka B', $payloadLabels);
        $response->assertDontSee('Guru Eka B');
    }

    public function test_create_lists_only_same_school_guru_when_target_role_guru(): void
    {
        $html = $this->actingAs($this->kepsek)
            ->withSession(['_old_input' => ['target_role' => 'guru']])
            ->get(route('kepala-sekolah.rencana-aksi.create'))
            ->assertOk()
            ->getContent();

        $labels = array_column($this->targetSelectState($html)['options'], 'label');

        $this->assertContains('Guru Andi A', $labels);
        $this->assertNotContains('Waka Budi A', $labels);
        $this->assertNotContains('Siswa Dewi A', $labels);
        $this->assertNotContains('Guru Eka B', $labels, 'Guru sekolah lain tidak boleh muncul.');
    }

    public function test_create_lists_only_same_school_waka_when_target_role_waka(): void
    {
        $html = $this->actingAs($this->kepsek)
            ->withSession(['_old_input' => ['target_role' => 'waka']])
            ->get(route('kepala-sekolah.rencana-aksi.create'))
            ->assertOk()
            ->getContent();

        $labels = array_column($this->targetSelectState($html)['options'], 'label');

        $this->assertContains('Waka Budi A', $labels);
        $this->assertNotContains('Guru Andi A', $labels);
        $this->assertNotContains('Siswa Dewi A', $labels);
        $this->assertNotContains('Guru Eka B', $labels, 'Waka sekolah lain tidak boleh muncul.');
    }

    public function test_create_carries_role_metadata_and_dynamic_filter_reset_handler(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.rencana-aksi.create'));

        $response->assertOk();
        $html = $response->getContent();

        // Frontend: Target Role onchange memanggil filter + mereset Target Orang.
        $this->assertStringContainsString('window.KS_ACTION_PLAN_FILTER(this.value)', $html);
        $this->assertStringContainsString("state.selectedVal = ''", $html);
        $this->assertStringContainsString('state.$refs.hiddenInput.value = \'\';', $html);

        $byLabel = [];
        foreach ($this->targetOptions($html) as $opt) {
            $byLabel[$opt['label']] = $opt['role'];
        }

        $this->assertSame('guru', $byLabel['Guru Andi A']);
        $this->assertSame('waka', $byLabel['Waka Budi A']);
        $this->assertSame('siswa', $byLabel['Siswa Dewi A']);
        $this->assertArrayNotHasKey('Guru Eka B', $byLabel);
    }

    public function test_rerender_after_failed_validation_only_lists_targets_matching_old_target_role(): void
    {
        // Submit tidak valid (judul kosong) dengan Target Role=Waka tapi Target Orang=Guru.
        $response = $this->actingAs($this->kepsek)
            ->from(route('kepala-sekolah.rencana-aksi.create'))
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => 'waka',
                'target_user_id' => $this->guruA->id,
                'title' => '',
            ]));

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('school_action_plans', 0);

        // Render ulang: daftar Target Orang (state Alpine) mengikuti Target Role lama (waka),
        // Guru A hilang dan tidak tetap terpilih.
        $html = $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.rencana-aksi.create'))
            ->assertOk()
            ->getContent();

        $state = $this->targetSelectState($html);
        $labels = array_column($state['options'], 'label');

        $this->assertContains('Waka Budi A', $labels);
        $this->assertNotContains('Guru Andi A', $labels, 'Target lama yang tidak valid terhadap Target Role harus hilang dari daftar.');
        $this->assertSame('', $state['value'], 'Target lama yang tidak valid harus di-reset ke placeholder.');
    }

    public function test_store_rejects_target_whose_role_does_not_match_target_role(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => 'guru',
                'target_user_id' => $this->siswaA->id,
            ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    public function test_store_rejects_target_from_another_school(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => 'guru',
                'target_user_id' => $this->guruB->id,
            ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    public function test_store_accepts_target_with_matching_role_and_school(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => 'guru',
                'target_user_id' => $this->guruA->id,
            ]));

        $response->assertRedirect(route('kepala-sekolah.rencana-aksi.index'));
        $this->assertDatabaseHas('school_action_plans', [
            'user_id' => $this->kepsek->id,
            'target_role' => 'guru',
            'target_user_id' => $this->guruA->id,
            'title' => 'Evaluasi pembelajaran semester ini',
        ]);
    }

    public function test_store_with_empty_target_role_allows_same_school_target(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => '',
                'target_user_id' => $this->guruA->id,
            ]));

        $response->assertRedirect(route('kepala-sekolah.rencana-aksi.index'));
        $this->assertDatabaseHas('school_action_plans', [
            'target_role' => null,
            'target_user_id' => $this->guruA->id,
        ]);
    }

    public function test_store_with_empty_target_role_still_rejects_other_school_target(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => '',
                'target_user_id' => $this->guruB->id,
            ]));

        $response->assertSessionHasErrors('target_user_id');
        $this->assertDatabaseCount('school_action_plans', 0);
    }

    public function test_store_without_target_person_is_allowed(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.rencana-aksi.store'), $this->payload([
                'target_role' => 'guru',
                'target_user_id' => '',
            ]));

        $response->assertRedirect(route('kepala-sekolah.rencana-aksi.index'));
        $this->assertDatabaseHas('school_action_plans', [
            'target_role' => 'guru',
            'target_user_id' => null,
        ]);
    }
}
