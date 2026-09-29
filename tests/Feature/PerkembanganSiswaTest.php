<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerkembanganSiswaTest extends TestCase
{
    use RefreshDatabase;

    private User $kepalaSekolah;
    private School $schoolA;
    private School $schoolB;
    private Role $roleKepalaSekolah;
    private Role $roleStudent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleKepalaSekolah = Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
        $this->roleStudent = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

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

        $this->kepalaSekolah = User::create([
            'role_id' => $this->roleKepalaSekolah->id,
            'school_id' => $this->schoolA->id,
            'name' => 'Kepala Sekolah A',
            'email' => 'kepala@sekolaha.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }

    private function createStudent(School $school, string $name, ?string $nis = null, ?string $nisn = null): Student
    {
        $rand = rand(10000, 99999);
        $user = User::create([
            'school_id' => $school->id,
            'role_id' => $this->roleStudent->id,
            'name' => $name,
            'email' => strtolower(str_replace(' ', '', $name)) . $rand . '@test.com',
            'password' => bcrypt('password'),
        ]);

        return Student::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'nis' => $nis ?? (string) $rand,
            'nisn' => $nisn ?? (string) ($rand + 100000),
            'gender' => 'L',
        ]);
    }

    public function test_kepala_sekolah_can_view_all_students_when_no_filters_applied(): void
    {
        $this->createStudent($this->schoolA, 'Siswa Alpha');
        $this->createStudent($this->schoolA, 'Siswa Beta');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan'));

        $response->assertOk();
        $response->assertSee('Siswa Alpha');
        $response->assertSee('Siswa Beta');
    }

    public function test_empty_filters_does_not_show_only_one_student(): void
    {
        $this->createStudent($this->schoolA, 'Siswa 1');
        $this->createStudent($this->schoolA, 'Siswa 2');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['class_id' => '', 'student_id' => '']));

        $response->assertOk();
        $response->assertSee('Siswa 1');
        $response->assertSee('Siswa 2');
    }

    public function test_filter_by_class_id_returns_only_class_students(): void
    {
        $academicYear = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'name' => '2025/2026',
            'year' => '2025/2026',
            'is_active' => true,
        ]);

        $classA = Classroom::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Kelas VII A',
            'grade_level' => '7',
            'academic_year_id' => $academicYear->id,
        ]);

        $classB = Classroom::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Kelas VII B',
            'grade_level' => '7',
            'academic_year_id' => $academicYear->id,
        ]);

        $student1 = $this->createStudent($this->schoolA, 'Siswa VII A');
        $student2 = $this->createStudent($this->schoolA, 'Siswa VII B');

        $student1->classes()->attach($classA->id, ['school_id' => $this->schoolA->id, 'academic_year_id' => $academicYear->id]);
        $student2->classes()->attach($classB->id, ['school_id' => $this->schoolA->id, 'academic_year_id' => $academicYear->id]);

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['class_id' => $classA->id]));

        $response->assertOk();
        $response->assertSee('Siswa VII A');
        $response->assertDontSee('Siswa VII B');
    }

    public function test_filter_by_student_id_shows_specific_student(): void
    {
        $student1 = $this->createStudent($this->schoolA, 'Siswa Pilihan', '1001', '2001');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['student_id' => $student1->id]));

        $response->assertOk();
        $response->assertSee('Siswa Pilihan');
        $response->assertSee('NIS: 1001');
    }

    public function test_student_id_from_other_school_returns_404(): void
    {
        $studentOther = $this->createStudent($this->schoolB, 'Siswa Sekolah Lain');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['student_id' => $studentOther->id]));

        $response->assertStatus(404);
    }

    public function test_class_id_from_other_school_returns_404(): void
    {
        $academicYearB = AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'name' => '2025/2026',
            'year' => '2025/2026',
            'is_active' => true,
        ]);

        $classOther = Classroom::create([
            'school_id' => $this->schoolB->id,
            'name' => 'Kelas Sekolah B',
            'grade_level' => '7',
            'academic_year_id' => $academicYearB->id,
        ]);

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['class_id' => $classOther->id]));

        $response->assertNotFound();
        $response->assertDontSee('Kelas Sekolah B');
    }

    public function test_manipulative_school_id_query_param_does_not_leak_data(): void
    {
        $this->createStudent($this->schoolB, 'Siswa Rahasia Sekolah B');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['school_id' => $this->schoolB->id]));

        $response->assertOk();
        $response->assertDontSee('Siswa Rahasia Sekolah B');
    }

    public function test_kepala_sekolah_a_cannot_see_students_from_school_b(): void
    {
        $this->createStudent($this->schoolA, 'Siswa Sekolah A');
        $this->createStudent($this->schoolB, 'Siswa Sekolah B');

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan'));

        $response->assertOk();
        $response->assertSee('Siswa Sekolah A');
        $response->assertDontSee('Siswa Sekolah B');
    }

    public function test_class_change_with_incompatible_student_resets_selection_instead_of_404(): void
    {
        [$year, $classA, $classB] = $this->makeClasses();
        $alpha = $this->createStudent($this->schoolA, 'Siswa Alpha');
        $this->attachToClass($alpha, $classA, $year);

        // Siswa dipilih dulu, lalu kelas diganti ke kelas yang tidak memilikinya.
        $response = $this->actingAs($this->kepalaSekolah)->get(route('kepala-sekolah.academic.perkembangan', [
            'class_id' => $classB->id,
            'student_id' => $alpha->id,
        ]));

        $response->assertOk();

        $state = $this->selectState($response->getContent(), 'student_id');
        $this->assertSame('', $state['value'], 'student_id lama harus di-reset saat kelas berubah.');
        // Label tombol di-resolve Alpine dari opsi value='' saat init (syncOptions()).
        $placeholders = array_values(array_filter($state['options'], fn ($o) => ($o['value'] ?? null) === ''));
        $this->assertNotEmpty($placeholders, 'Opsi placeholder reset harus tersedia.');
        $this->assertSame('-- Pilih Siswa --', $placeholders[0]['label'] ?? null);
        $this->assertNotContains('Siswa Alpha', array_column($state['options'], 'label'), 'Siswa yang bukan anggota kelas tidak boleh tersisa di dropdown.');
        $response->assertDontSee('Lihat Detail Penuh');
    }

    public function test_student_dropdown_lists_only_members_of_selected_class(): void
    {
        [$year, $classA, $classB] = $this->makeClasses();
        $alpha = $this->createStudent($this->schoolA, 'Siswa Alpha');
        $beta = $this->createStudent($this->schoolA, 'Siswa Beta');
        $this->attachToClass($alpha, $classA, $year);
        $this->attachToClass($beta, $classB, $year);

        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan', ['class_id' => $classB->id]));

        $response->assertOk();

        $state = $this->selectState($response->getContent(), 'student_id');
        $labels = array_column($state['options'], 'label');
        $this->assertSame('', $state['value']);
        $this->assertContains('Siswa Beta', $labels);
        $this->assertNotContains('Siswa Alpha', $labels);
    }

    public function test_valid_class_and_student_combination_shows_detail(): void
    {
        [$year, $classA] = $this->makeClasses();
        $alpha = $this->createStudent($this->schoolA, 'Siswa Alpha');
        $this->attachToClass($alpha, $classA, $year);

        $response = $this->actingAs($this->kepalaSekolah)->get(route('kepala-sekolah.academic.perkembangan', [
            'class_id' => $classA->id,
            'student_id' => $alpha->id,
        ]));

        $response->assertOk();
        $response->assertSee('Lihat Detail Penuh');

        $state = $this->selectState($response->getContent(), 'student_id');
        $this->assertSame((string) $alpha->id, $state['value']);
    }

    public function test_repeated_class_switching_keeps_student_selection_consistent(): void
    {
        [$year, $classA, $classB] = $this->makeClasses();
        $alpha = $this->createStudent($this->schoolA, 'Siswa Alpha');
        $this->attachToClass($alpha, $classA, $year);

        $sequence = [$classB->id, $classA->id, $classB->id];

        foreach ($sequence as $classId) {
            $response = $this->actingAs($this->kepalaSekolah)->get(route('kepala-sekolah.academic.perkembangan', [
                'class_id' => $classId,
                'student_id' => $alpha->id,
            ]));

            $response->assertOk();
            $state = $this->selectState($response->getContent(), 'student_id');

            if ($classId === $classA->id) {
                $this->assertSame((string) $alpha->id, $state['value'], 'Siswa anggota kelas harus tetap terpilih.');
                $response->assertSee('Lihat Detail Penuh');
            } else {
                $this->assertSame('', $state['value'], 'Pilihan siswa harus di-reset untuk kelas yang tidak memilikinya.');
                $response->assertDontSee('Lihat Detail Penuh');
            }
        }
    }

    public function test_class_change_handler_clears_student_before_submitting_form(): void
    {
        $response = $this->actingAs($this->kepalaSekolah)
            ->get(route('kepala-sekolah.academic.perkembangan'));

        $response->assertOk();
        $this->assertStringContainsString(
            "document.getElementById('student_id').value = ''",
            $response->getContent(),
            'Dropdown Kelas harus mereset student_id sebelum submit.'
        );
    }

    public function test_student_from_another_school_with_class_filter_still_returns_404(): void
    {
        [, $classA] = $this->makeClasses();
        $foreign = $this->createStudent($this->schoolB, 'Siswa Sekolah Lain');

        $response = $this->actingAs($this->kepalaSekolah)->get(route('kepala-sekolah.academic.perkembangan', [
            'class_id' => $classA->id,
            'student_id' => $foreign->id,
        ]));

        $response->assertNotFound();
    }

    public function test_non_kepala_sekolah_role_cannot_access_perkembangan(): void
    {
        $siswaRole = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);
        $user = User::create([
            'role_id' => $siswaRole->id,
            'school_id' => $this->schoolA->id,
            'name' => 'Siswa Biasa',
            'email' => 'siswa.biasa@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('kepala-sekolah.academic.perkembangan'))
            ->assertForbidden();
    }

    /**
     * @return array{0: AcademicYear, 1: Classroom, 2: Classroom}
     */
    private function makeClasses(): array
    {
        $year = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2025/2026',
            'is_active' => true,
        ]);

        $classA = Classroom::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Kelas VII A',
            'grade_level' => '7',
            'academic_year_id' => $year->id,
        ]);

        $classB = Classroom::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Kelas VII B',
            'grade_level' => '7',
            'academic_year_id' => $year->id,
        ]);

        return [$year, $classA, $classB];
    }

    private function attachToClass(Student $student, Classroom $class, AcademicYear $academicYear): void
    {
        $student->classes()->attach($class->id, [
            'school_id' => $this->schoolA->id,
            'academic_year_id' => $academicYear->id,
        ]);
    }

    /**
     * Baca state Alpine dari select berdasarkan id input hidden-nya.
     *
     * @return array{value: ?string, label: ?string, options: array<int, array{value: string, label: string}>}
     */
    private function selectState(string $html, string $id): array
    {
        $pos = strpos($html, 'id="'.$id.'"');
        $this->assertNotFalse($pos, "Select #{$id} tidak dirender di halaman.");
        $start = strrpos(substr($html, 0, $pos), 'x-data="{');
        $this->assertNotFalse($start, "x-data untuk #{$id} tidak ditemukan.");
        $block = substr($html, $start, $pos - $start);

        preg_match("/selectedVal: '((?:\\\\.|[^'])*)'/", $block, $value);
        preg_match("/selectedLabel: '((?:\\\\.|[^'])*)'/", $block, $label);
        preg_match("/options: JSON\.parse\('((?:\\\\.|[^'])*)'\)/", $block, $opts);

        $options = [];
        if (isset($opts[1])) {
            $decoded = json_decode(str_replace('\u0022', '"', $opts[1]), true);
            $this->assertIsArray($decoded, "Opsi #{$id} gagal dibaca: json_decode error.");
            $options = $decoded;
        }

        return [
            'value' => $value[1] ?? null,
            'label' => $label[1] ?? null,
            'options' => $options,
        ];
    }
}
