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
 * Tambah Semester (Waka):
 * - Ganjil + Genap BOLEH pada tahun ajaran yang sama;
 * - yang ditolak hanya semester SAMA pada tahun ajaran YANG SAMA;
 * - tanpa 500 (regression: import SemesterRequest hilang di controller).
 */
class SemesterCreateTest extends TestCase
{
    use RefreshDatabase;

    protected User $waka;

    protected School $school;

    protected AcademicYear $year;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'npsn' => '777777',
            'name' => 'SMP Waka Semester',
            'email' => 'waka.semester@test.com',
            'is_active' => true,
        ]);
        $wakaRole = Role::firstOrCreate(['name' => 'waka'], ['display_name' => 'Waka']);
        $this->waka = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => $wakaRole->id,
        ]);
        $this->year = AcademicYear::create([
            'school_id' => $this->school->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);
    }

    private function store(array $data)
    {
        return $this->actingAs($this->waka)
            ->from(route('waka.semesters.create'))
            ->post(route('waka.semesters.store'), $data);
    }

    public function test_store_ganjil_succeeds(): void
    {
        $this->store([
            'academic_year_id' => $this->year->id,
            'name' => 'Ganjil',
        ])->assertRedirect(route('waka.semesters.index'))
            ->assertSessionHas('success', 'Semester berhasil ditambahkan.');

        $this->assertDatabaseCount('semesters', 1);
        $this->assertDatabaseHas('semesters', [
            'academic_year_id' => $this->year->id,
            'name' => 'Ganjil',
        ]);
    }

    public function test_genap_can_join_same_academic_year_as_ganjil(): void
    {
        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Ganjil'])
            ->assertSessionHas('success');

        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Genap'])
            ->assertRedirect(route('waka.semesters.index'))
            ->assertSessionHas('success', 'Semester berhasil ditambahkan.');

        $this->assertDatabaseCount('semesters', 2);
    }

    public function test_duplicate_ganjil_on_same_academic_year_is_rejected(): void
    {
        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Ganjil'])
            ->assertSessionHas('success');

        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Ganjil'])
            ->assertRedirect(route('waka.semesters.create'))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('semesters', 1);
    }

    public function test_duplicate_genap_on_same_academic_year_is_rejected(): void
    {
        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Genap'])
            ->assertSessionHas('success');

        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Genap'])
            ->assertRedirect(route('waka.semesters.create'))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('semesters', 1);
    }

    public function test_same_semester_name_on_different_academic_year_is_allowed(): void
    {
        $nextYear = AcademicYear::create([
            'school_id' => $this->school->id,
            'year' => '2027/2028',
            'is_active' => false,
        ]);

        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Ganjil'])
            ->assertSessionHas('success');

        $this->store(['academic_year_id' => $nextYear->id, 'name' => 'Ganjil'])
            ->assertRedirect(route('waka.semesters.index'))
            ->assertSessionHas('success', 'Semester berhasil ditambahkan.');

        $this->assertDatabaseCount('semesters', 2);
    }

    public function test_update_does_not_conflict_with_itself(): void
    {
        $this->store(['academic_year_id' => $this->year->id, 'name' => 'Ganjil'])
            ->assertSessionHas('success');

        $semesterId = (int) \DB::table('semesters')->value('id');

        $this->actingAs($this->waka)
            ->from(route('waka.semesters.index'))
            ->put(route('waka.semesters.update', $semesterId), [
                'academic_year_id' => $this->year->id,
                'name' => 'Ganjil',
            ])
            ->assertRedirect(route('waka.semesters.index'))
            ->assertSessionHas('success', 'Semester berhasil diperbarui.');

        $this->assertDatabaseCount('semesters', 1);
    }
}
