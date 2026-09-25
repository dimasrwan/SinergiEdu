<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchoolDeletionEligibilityService
{
    /**
     * Periksa apakah sekolah eligible untuk dihapus permanen.
     *
     * @param School $school
     * @return array{eligible: bool, reasons: array<string>, counts: array<string, int>}
     */
    public function check(School $school): array
    {
        $reasons = [];
        $counts = [];

        $tables = [
            'users' => ['label' => 'pengguna', 'msg' => 'pengguna terdaftar (Admin/Guru/Siswa/Orang Tua)'],
            'teachers' => ['label' => 'guru', 'msg' => 'data profil guru'],
            'students' => ['label' => 'siswa', 'msg' => 'data profil siswa'],
            'classrooms' => ['label' => 'kelas', 'msg' => 'ruang kelas'],
            'subjects' => ['label' => 'mata_pelajaran', 'msg' => 'mata pelajaran'],
            'academic_years' => ['label' => 'tahun_ajaran', 'msg' => 'data tahun ajaran'],
            'inspections' => ['label' => 'inspeksi', 'msg' => 'riwayat inspeksi pengawasan'],
            'student_parents' => ['label' => 'orang_tua', 'msg' => 'data orang tua'],
            'wakas' => ['label' => 'waka', 'msg' => 'data waka'],
            'kepala_sekolahs' => ['label' => 'kepala_sekolah', 'msg' => 'data kepala sekolah'],
        ];

        foreach ($tables as $table => $info) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->where('school_id', $school->id)->count();
                $counts[$info['label']] = $count;
                if ($count > 0) {
                    $reasons[] = "Memiliki {$count} {$info['msg']}.";
                }
            } else {
                $counts[$info['label']] = 0;
            }
        }

        return [
            'eligible' => empty($reasons),
            'reasons' => $reasons,
            'counts' => $counts,
        ];
    }
}
