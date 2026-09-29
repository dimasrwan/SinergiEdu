<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Feedback;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class StrategicFeedbackTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;
    private User $kepsek;
    private User $guruA;
    private User $wakaA;
    private User $pengawasA;
    private User $siswaA;
    private User $guruB;
    private User $pengawasB;

    protected function setUp(): void
    {
        parent::setUp();

        $roleKepsek = Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
        $roleGuru = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);
        $roleWaka = Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka Kurikulum']);
        $rolePengawas = Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $this->schoolA = School::create([
            'npsn' => '11111111',
            'name' => 'Sekolah A',
            'email' => 'sekolaha@test.com',
            'is_active' => true,
        ]);

        $this->schoolB = School::create([
            'npsn' => '22222222',
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
        $this->pengawasA = $make($rolePengawas, $this->schoolA, 'Pengawas Candra A', 'pengawas.a@test.com');
        $this->siswaA = $make($roleSiswa, $this->schoolA, 'Siswa Dewi A', 'siswa.a@test.com');
        $this->guruB = $make($roleGuru, $this->schoolB, 'Guru Eka B', 'guru.b@test.com');
        $this->pengawasB = $make($rolePengawas, $this->schoolB, 'Pengawas Fajar B', 'pengawas.b@test.com');
    }

    /**
     * Decode JSON daftar penerima (window.KS_FEEDBACK_RECIPIENTS) dari HTML render.
     *
     * @return array<int, array{value: string, label: string, role: ?string}>
     */
    private function recipientOptions(string $html): array
    {
        $this->assertStringContainsString('window.KS_FEEDBACK_RECIPIENTS', $html, 'Payload daftar penerima tidak dirender.');
        preg_match('/window\.KS_FEEDBACK_RECIPIENTS = (.*);/', $html, $m);
        $this->assertNotEmpty($m, 'Assignment KS_FEEDBACK_RECIPIENTS tidak ditemukan.');

        $raw = trim($m[1]);
        // @js() merender JSON.parse('...') dengan quote di-escape ke \u0022.
        if (preg_match("/^JSON\\.parse\\('(.*)'\\)$/", $raw, $inner)) {
            $raw = $inner[1];
        }

        $decoded = json_decode(str_replace('\u0022', '"', $raw), true);
        $this->assertIsArray($decoded, 'Payload KS_FEEDBACK_RECIPIENTS bukan JSON valid.');

        return $decoded;
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'recipient_role' => 'guru',
            'recipient_id' => $this->guruA->id,
            'category' => 'strategic',
            'priority' => 'high',
            'title' => 'Evaluasi pembelajaran',
            'message' => 'Mohon evaluasi metode pembelajaran minggu ini.',
        ], $overrides);
    }

    public function test_create_lists_all_users_of_same_school_and_no_other_school(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.feedback.create'));

        $response->assertOk();

        $options = $this->recipientOptions($response->getContent());
        $labels = array_column($options, 'label');

        // Tujuan kosong → semua user sekolah yang sama (semua role valid).
        $this->assertContains('-- Semua (Umum) --', $labels);
        $this->assertContains('Guru Andi A', $labels);
        $this->assertContains('Waka Budi A', $labels);
        $this->assertContains('Pengawas Candra A', $labels);
        $this->assertContains('Siswa Dewi A', $labels);

        // Tenant isolation: user sekolah lain tidak boleh muncul sama sekali.
        $this->assertNotContains('Guru Eka B', $labels);
        $this->assertNotContains('Pengawas Fajar B', $labels);
        $response->assertDontSee('Guru Eka B');
        $response->assertDontSee('Pengawas Fajar B');
    }

    public function test_create_carries_role_metadata_and_dynamic_filter_reset_handler(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.feedback.create'));

        $response->assertOk();
        $html = $response->getContent();

        // Frontend: Tujuan onchange memanggil filter + mereset Penerima.
        $this->assertStringContainsString('window.KS_FEEDBACK_FILTER(this.value)', $html);
        $this->assertStringContainsString("state.selectedVal = ''", $html);
        $this->assertStringContainsString('state.$refs.hiddenInput.value = \'\';', $html);

        // Role tiap penerima ikut terbawa (dipakai filter by Tujuan di browser).
        $byLabel = [];
        foreach ($this->recipientOptions($html) as $opt) {
            $byLabel[$opt['label']] = $opt['role'];
        }

        $this->assertSame('guru', $byLabel['Guru Andi A']);
        $this->assertSame('waka', $byLabel['Waka Budi A']);
        $this->assertSame('pengawas', $byLabel['Pengawas Candra A']);
        $this->assertSame('siswa', $byLabel['Siswa Dewi A']);
        $this->assertArrayNotHasKey('Guru Eka B', $byLabel);
    }

    public function test_rerender_after_failed_validation_only_lists_recipients_matching_old_target_role(): void
    {
        // Submit tidak valid (judul kosong) dengan Tujuan=Waka tapi Penerima=Guru.
        $response = $this->actingAs($this->kepsek)
            ->from(route('kepala-sekolah.feedback.create'))
            ->post(route('kepala-sekolah.feedback.store'), $this->payload([
                'recipient_role' => 'waka',
                'recipient_id' => $this->guruA->id,
                'title' => '',
            ]));

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('feedbacks', 0);

        // Render ulang: daftar Penerima (state Alpine) mengikuti role Tujuan lama (waka).
        $html = $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.feedback.create'))
            ->assertOk()
            ->getContent();

        $state = $this->recipientSelectState($html);
        $labels = array_column($state['options'], 'label');

        $this->assertContains('Waka Budi A', $labels);
        $this->assertNotContains('Guru Andi A', $labels, 'Penerima lama yang tidak valid terhadap Tujuan harus hilang dari daftar.');
        $this->assertSame('', $state['value'], 'Penerima lama yang tidak valid harus di-reset ke placeholder.');
    }

    /**
     * Baca state Alpine select Penerima (selectedVal + options) dari HTML render.
     *
     * @return array{value: ?string, options: array<int, array{value: string, label: string}>}
     */
    private function recipientSelectState(string $html): array
    {
        $pos = strpos($html, 'id="recipient_id"');
        $this->assertNotFalse($pos, 'Select Penerima tidak dirender.');
        $start = strrpos(substr($html, 0, $pos), 'x-data="{');
        $this->assertNotFalse($start, 'x-data select Penerima tidak ditemukan.');
        $block = substr($html, $start, $pos - $start);

        preg_match("/selectedVal: '((?:\\\\.|[^'])*)'/", $block, $value);
        preg_match("/options: JSON\.parse\('((?:\\\\.|[^'])*)'\)/", $block, $opts);

        $options = [];
        if (isset($opts[1])) {
            $decoded = json_decode(str_replace('\u0022', '"', $opts[1]), true);
            $this->assertIsArray($decoded, 'Options select Penerima bukan JSON valid.');
            $options = $decoded;
        }

        return ['value' => $value[1] ?? null, 'options' => $options];
    }

    public function test_store_rejects_recipient_whose_role_does_not_match_target_role(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.feedback.store'), $this->payload([
                'recipient_role' => 'guru',
                'recipient_id' => $this->siswaA->id,
            ]));

        $response->assertSessionHasErrors('recipient_id');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_store_rejects_recipient_from_another_school(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.feedback.store'), $this->payload([
                'recipient_role' => 'guru',
                'recipient_id' => $this->guruB->id,
            ]));

        $response->assertSessionHasErrors('recipient_id');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_store_rejects_recipient_from_another_school_even_for_pengawas(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.feedback.store'), $this->payload([
                'recipient_role' => 'pengawas',
                'recipient_id' => $this->pengawasB->id,
            ]));

        $response->assertSessionHasErrors('recipient_id');
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_store_accepts_recipient_with_matching_role_and_school(): void
    {
        $response = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.feedback.store'), $this->payload([
                'recipient_role' => 'guru',
                'recipient_id' => $this->guruA->id,
            ]));

        $response->assertRedirect(route('kepala-sekolah.feedback.index'));
        $this->assertDatabaseHas('feedbacks', [
            'sender_id' => $this->kepsek->id,
            'recipient_role' => 'guru',
            'recipient_id' => $this->guruA->id,
            'title' => 'Evaluasi pembelajaran',
            'status' => 'sent',
        ]);
    }

    public function test_store_without_target_role_is_rejected_and_cross_school_still_rejected(): void
    {
        // Rule lama recipient_role required tetap berlaku (tidak diubah).
        $noRoleSameSchool = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.feedback.store'), $this->payload([
                'recipient_role' => '',
                'recipient_id' => $this->guruA->id,
            ]));
        $noRoleSameSchool->assertSessionHasErrors('recipient_role');
        $this->assertDatabaseCount('feedbacks', 0);

        // Tanpa role pun, penerima lintas sekolah tetap harus ditolak.
        $noRoleCrossSchool = $this->actingAs($this->kepsek)
            ->post(route('kepala-sekolah.feedback.store'), $this->payload([
                'recipient_role' => '',
                'recipient_id' => $this->guruB->id,
            ]));
        $noRoleCrossSchool->assertSessionHasErrors(['recipient_role', 'recipient_id']);
        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_detail_feedback_shows_information_without_status_update_card(): void
    {
        $feedback = Feedback::create([
            'sender_id' => $this->kepsek->id,
            'recipient_role' => 'guru',
            'recipient_id' => $this->guruA->id,
            'title' => 'Judul Detail Feedback',
            'message' => 'Isi feedback untuk pengujian detail.',
            'type' => 'neutral',
            'category' => 'academic',
            'priority' => 'high',
            'status' => 'sent',
        ]);

        $response = $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.feedback.show', $feedback));

        $response->assertOk();

        // Card "Perbarui Status" + dropdown + button dihapus.
        $response->assertDontSee('Perbarui Status');
        $response->assertDontSee('Simpan Status');
        $response->assertDontSee('name="status"', false);
        $this->assertFalse(
            Route::has('kepala-sekolah.feedback.update-status'),
            'Route update status harus ikut dihapus (hanya dipakai card yang dihapus).'
        );

        // Bagian informasi tetap tampil.
        $response->assertSee('Judul Detail Feedback');
        $response->assertSee('Isi Feedback');
        $response->assertSee('Informasi');
        $response->assertSee('Pengirim');
        $response->assertSee('Penerima');
        $response->assertSee('Dibuat');
        $response->assertSee('Terkirim'); // badge status sebagai informasi
        $response->assertSee('Kembali ke Daftar Feedback');

        // Daftar feedback tetap bisa dibuka.
        $this->actingAs($this->kepsek)
            ->get(route('kepala-sekolah.feedback.index'))
            ->assertOk();
    }
}
