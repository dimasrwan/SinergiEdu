<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Semester;
use App\Services\TenantService;
use Illuminate\Database\Seeder;

/**
 * Seeder data akademik global: Tahun Ajaran & Semester.
 *
 * Data yang dihasilkan per sekolah:
 *   - 2026/2027 -> Semester Ganjil 2026 (aktif) & Semester Genap 2027
 *   - 2027/2028 -> Semester Ganjil 2027
 *
 * Idempotent: updateOrCreate sehingga aman dijalankan berulang kali.
 */
class AcademicSeeder extends Seeder
{
    protected const ACTIVE_YEAR = '2026/2027';

    /**
     * Struktur tahun ajaran => daftar semester.
     *
     * @var array<string, array<int, array{name: string, is_active: bool}>>
     */
    protected array $academicData = [
        '2026/2027' => [
            ['name' => 'Ganjil', 'is_active' => true],
            ['name' => 'Genap', 'is_active' => false],
        ],
        '2027/2028' => [
            ['name' => 'Ganjil', 'is_active' => false],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();

        if ($schools->isEmpty()) {
            return;
        }

        foreach ($schools as $school) {
            $this->seedForSchool($school);
        }

        app(TenantService::class)->clear();
    }

    protected function seedForSchool(School $school): void
    {
        app(TenantService::class)->setSchool($school);

        foreach ($this->academicData as $year => $semesters) {
            $yearStr = $year . ' (' . $school->name . ')';
            $academicYear = AcademicYear::firstOrCreate(
                ['school_id' => $school->id, 'year' => $yearStr],
                ['is_active' => $year === self::ACTIVE_YEAR]
            );

            foreach ($semesters as $semester) {
                $semesterName = $semester['name'] . ' (' . $school->name . ')';
                Semester::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'academic_year_id' => $academicYear->id,
                        'name' => $semesterName,
                    ],
                    ['is_active' => $semester['is_active']]
                );
            }
        }

        // Jamin tepat satu tahun ajaran aktif (2026/2027) dan satu semester aktif (Ganjil).
        $activeYearStr = self::ACTIVE_YEAR . ' (' . $school->name . ')';
        $activeYear = AcademicYear::where('year', $activeYearStr)->first();
        AcademicYear::where('school_id', $school->id)->where('id', '!=', $activeYear?->id)->update(['is_active' => false]);
        if ($activeYear) {
            $activeYear->update(['is_active' => true]);
        }

        $activeSemester = Semester::where('school_id', $school->id)->where('is_active', true)->first();
        if ($activeSemester) {
            Semester::where('school_id', $school->id)->where('id', '!=', $activeSemester->id)->update(['is_active' => false]);
            $activeSemester->update(['is_active' => true]);
        }
    }
}
