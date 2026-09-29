<?php

declare(strict_types=1);

namespace Tests\Feature\KepalaSekolah;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class RekapNilaiTest extends TestCase
{
    use RefreshDatabase;

    private User $kepala;
    private School $schoolA;
    private AcademicYear $yearA;
    private Semester $ganjil;
    private Semester $genap;
    private Classroom $classA;
    private Classroom $classB;
    private Subject $math;
    private Subject $science;
    private Student $studentGanjil;
    private Student $studentGenap;
    private Student $foreignStudent;

    protected function setUp(): void
    {
        parent::setUp();

        $roleKepala = Role::firstOrCreate(['name' => 'kepala_sekolah'], ['display_name' => 'Kepala Sekolah']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa'], ['display_name' => 'Siswa']);

        $this->schoolA = School::create([
            'npsn' => '11111111',
            'name' => 'Sekolah A',
            'email' => 'sekolaha@test.com',
            'is_active' => true,
        ]);
        $schoolB = School::create([
            'npsn' => '22222222',
            'name' => 'Sekolah B',
            'email' => 'sekolahb@test.com',
            'is_active' => true,
        ]);

        $this->kepala = User::create([
            'role_id' => $roleKepala->id,
            'school_id' => $this->schoolA->id,
            'name' => 'Kepala Sekolah A',
            'email' => 'kepala@sekolaha.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->yearA = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => true]);
        $yearB = AcademicYear::create(['school_id' => $schoolB->id, 'year' => '2026/2027', 'is_active' => true]);

        $this->ganjil = Semester::create(['school_id' => $this->schoolA->id, 'academic_year_id' => $this->yearA->id, 'name' => 'Ganjil', 'is_active' => true]);
        $this->genap = Semester::create(['school_id' => $this->schoolA->id, 'academic_year_id' => $this->yearA->id, 'name' => 'Genap', 'is_active' => false]);
        $semesterB = Semester::create(['school_id' => $schoolB->id, 'academic_year_id' => $yearB->id, 'name' => 'Ganjil', 'is_active' => true]);

        $this->classA = Classroom::create(['school_id' => $this->schoolA->id, 'name' => 'VII A', 'grade_level' => '7', 'academic_year_id' => $this->yearA->id]);
        $this->classB = Classroom::create(['school_id' => $this->schoolA->id, 'name' => 'VII B', 'grade_level' => '7', 'academic_year_id' => $this->yearA->id]);
        $classB2 = Classroom::create(['school_id' => $schoolB->id, 'name' => 'X IPA 1', 'grade_level' => '10', 'academic_year_id' => $yearB->id]);

        $this->math = Subject::create(['school_id' => $this->schoolA->id, 'name' => 'Matematika', 'code' => 'MTK']);
        $this->science = Subject::create(['school_id' => $this->schoolA->id, 'name' => 'IPA', 'code' => 'IPA']);
        $subjectB = Subject::create(['school_id' => $schoolB->id, 'name' => 'Biologi', 'code' => 'BIO']);

        $this->studentGanjil = $this->makeStudent($roleSiswa->id, $this->schoolA->id, 'Siswa Ganjil');
        $this->studentGenap = $this->makeStudent($roleSiswa->id, $this->schoolA->id, 'Siswa Genap');
        $this->foreignStudent = $this->makeStudent($roleSiswa->id, $schoolB->id, 'Siswa Sekolah Lain');

        $this->makeGrade($this->studentGanjil, $this->classA, $this->math, $this->ganjil, 80);
        $this->makeGrade($this->studentGenap, $this->classB, $this->science, $this->genap, 90);
        $this->makeGrade($this->foreignStudent, $classB2, $subjectB, $semesterB, 70);
    }

    private function makeStudent(int $roleId, int $schoolId, string $name): Student
    {
        $user = User::create([
            'school_id' => $schoolId,
            'role_id' => $roleId,
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)) . '@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        return Student::create([
            'school_id' => $schoolId,
            'user_id' => $user->id,
            'nis' => '100' . $user->id,
            'nisn' => '200' . $user->id,
            'gender' => 'L',
        ]);
    }

    private function makeGrade(Student $student, Classroom $class, Subject $subject, Semester $semester, int $score): StudentGrade
    {
        return StudentGrade::create([
            'student_id' => $student->id,
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $semester->academic_year_id,
            'semester_id' => $semester->id,
            'assignment_score' => $score,
        ]);
    }

    public function test_rekap_page_renders_semester_dropdown(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('kepala-sekolah.academic.rekap'));

        $response->assertOk();
        // Komponen select semester harus ter-compile menjadi kontrol, bukan tag literal.
        $response->assertDontSee('<x-select', false);
        $response->assertSee('id="semester_id"', false);
        $response->assertSee('-- Semua Semester --');
        $response->assertSee('Semester Ganjil 2026');
        $response->assertSee('Semester Genap 2027');
    }

    public function test_rekap_page_semester_filter_options_work(): void
    {
        $all = $this->actingAs($this->kepala)->get(route('kepala-sekolah.academic.rekap'));
        $all->assertOk();
        // Default halaman = semester aktif (Ganjil).
        $all->assertSee('Siswa Ganjil');
        $all->assertDontSee('Siswa Genap');

        $genap = $this->actingAs($this->kepala)->get(route('kepala-sekolah.academic.rekap', ['semester_id' => $this->genap->id]));
        $genap->assertOk();
        $genap->assertSee('Siswa Genap');
        $genap->assertDontSee('Siswa Ganjil');

        $empty = $this->actingAs($this->kepala)->get(route('kepala-sekolah.academic.rekap', ['semester_id' => '']));
        $empty->assertOk();
        $empty->assertSee('Siswa Ganjil');
        $empty->assertSee('Siswa Genap');
        $empty->assertDontSee('Siswa Sekolah Lain');
    }

    public function test_rekap_page_class_and_subject_filters_work(): void
    {
        $byClass = $this->actingAs($this->kepala)->get(route('kepala-sekolah.academic.rekap', ['semester_id' => '', 'class_id' => $this->classB->id]));
        $byClass->assertOk();
        $byClass->assertSee('Siswa Genap');
        $byClass->assertDontSee('Siswa Ganjil');

        $bySubject = $this->actingAs($this->kepala)->get(route('kepala-sekolah.academic.rekap', ['semester_id' => '', 'subject_id' => $this->math->id]));
        $bySubject->assertOk();
        $bySubject->assertSee('Siswa Ganjil');
        $bySubject->assertDontSee('Siswa Genap');
    }

    public function test_export_button_carries_current_filters(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('kepala-sekolah.academic.rekap', ['class_id' => $this->classB->id]));

        $response->assertOk();
        $response->assertSee(
            route('kepala-sekolah.reports.export-rekap-excel', ['class_id' => $this->classB->id]),
            false
        );
    }

    public function test_export_without_filter_downloads_xlsx(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('kepala-sekolah.reports.export-rekap-excel'));

        $response->assertOk();
        $response->assertDownload('rekap-nilai-siswa.xlsx');

        $values = $this->xlsxValues($response);
        $this->assertContains('Nama Siswa', $values);
        $this->assertContains('Siswa Ganjil', $values);
        // Data sekolah lain tidak boleh bocor / memicu error.
        $this->assertNotContains('Siswa Sekolah Lain', $values);
    }

    public function test_export_with_filters_respects_filters(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('kepala-sekolah.reports.export-rekap-excel', [
            'semester_id' => $this->genap->id,
            'class_id' => $this->classB->id,
            'subject_id' => $this->science->id,
        ]));

        $response->assertOk();
        $response->assertDownload('rekap-nilai-siswa.xlsx');

        $values = $this->xlsxValues($response);
        $this->assertContains('Siswa Genap', $values);
        $this->assertNotContains('Siswa Ganjil', $values);
        $this->assertNotContains('Siswa Sekolah Lain', $values);
    }

    public function test_export_with_all_semesters_filter_returns_all_own_school_rows(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('kepala-sekolah.reports.export-rekap-excel', ['semester_id' => '']));

        $response->assertOk();
        $response->assertDownload('rekap-nilai-siswa.xlsx');

        $values = $this->xlsxValues($response);
        $this->assertContains('Siswa Ganjil', $values);
        $this->assertContains('Siswa Genap', $values);
        $this->assertNotContains('Siswa Sekolah Lain', $values);
    }

    /**
     * @return array<int, mixed> Semua nilai sel dari file Excel hasil response.
     */
    private function xlsxValues(TestResponse $response): array
    {
        $path = $response->baseResponse->getFile()->getPathname();
        $sheet = IOFactory::createReaderForFile($path)->load($path)->getActiveSheet();
        $values = [];

        foreach ($sheet->toArray(null, true, true, false) as $row) {
            foreach ($row as $cell) {
                $values[] = is_string($cell) ? trim($cell) : $cell;
            }
        }

        return $values;
    }
}
