<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Services\KepalaSekolah\AcademicAggregatorService;
use Illuminate\View\View;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class ReportController extends Controller
{
    public function index(AcademicAggregatorService $aggregator): View
    {
        $semesters = Semester::with('academicYear')->get();
        $selectedSemester = request()->input('semester_id', $aggregator->activeSemester()?->id);

        $rows = $aggregator->getRekapList(auth()->user()->school_id, null, $selectedSemester);

        return view('pages.kepala-sekolah.laporan.index', compact('semesters', 'selectedSemester', 'rows'));
    }

    public function weeklyRecap(AcademicAggregatorService $aggregator): View
    {
        $componentAverages = $aggregator->getComponentAverages(auth()->user()->school_id);
        $classRankings = $aggregator->getClassRankings(auth()->user()->school_id);

        return view('pages.kepala-sekolah.laporan.rekap-mingguan', compact('componentAverages', 'classRankings'));
    }

    public function monthlyRecap(AcademicAggregatorService $aggregator): View
    {
        $subjectAnalysis = $aggregator->getSubjectAnalysis(auth()->user()->school_id);
        $classRankings = $aggregator->getClassRankings(auth()->user()->school_id);

        return view('pages.kepala-sekolah.laporan.rekap-bulanan', compact('subjectAnalysis', 'classRankings'));
    }

    public function semesterRecap(AcademicAggregatorService $aggregator): View
    {
        $componentAverages = $aggregator->getComponentAverages(auth()->user()->school_id);
        $classRankings = $aggregator->getClassRankings(auth()->user()->school_id);
        $subjectAnalysis = $aggregator->getSubjectAnalysis(auth()->user()->school_id);
        $schoolAvgGrade = $aggregator->getSchoolAverageGrade(auth()->user()->school_id);

        return view('pages.kepala-sekolah.laporan.rekap-semester', compact(
            'componentAverages', 'classRankings', 'subjectAnalysis', 'schoolAvgGrade'
        ));
    }

    public function approve(\Illuminate\Http\Request $request)
    {
        return back()->with('success', 'Laporan telah disetujui dan ditandatangani.');
    }

    public function exportSemesterPdf(AcademicAggregatorService $aggregator)
    {
        $schoolAvgGrade = $aggregator->getSchoolAverageGrade(auth()->user()->school_id);
        $classRankings = $aggregator->getClassRankings(auth()->user()->school_id);
        $componentAverages = $aggregator->getComponentAverages(auth()->user()->school_id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.kepala-sekolah.laporan.pdf-semester', compact(
            'schoolAvgGrade', 'classRankings', 'componentAverages'
        ));

        return $pdf->download('rekap-semester.pdf');
    }

    public function exportRekapExcel(AcademicAggregatorService $aggregator)
    {
        $rows = $aggregator->getRekapList(auth()->user()->school_id);

        $filePath = tempnam(sys_get_temp_dir(), 'excel_') . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($filePath);

        // Header Row
        $writer->addRow(Row::fromValues([
            'Nama Siswa',
            'NISN',
            'NIS',
            'Kelas',
            'Rata-rata',
            'Pretest',
            'Tugas',
            'Posttest',
            'Karakter',
            'Hafalan',
        ]));

        // Data Rows
        foreach ($rows as $row) {
            $writer->addRow(Row::fromValues([
                (string) ($row['name'] ?? ''),
                (string) ($row['nisn'] ?? ''),
                (string) ($row['nis'] ?? ''),
                (string) ($row['class_name'] ?? ''),
                is_numeric($row['avg'] ?? null) ? (float) $row['avg'] : 0,
                is_numeric($row['avg_pre_test'] ?? null) ? (float) $row['avg_pre_test'] : 0,
                is_numeric($row['avg_assignment'] ?? null) ? (float) $row['avg_assignment'] : 0,
                is_numeric($row['avg_post_test'] ?? null) ? (float) $row['avg_post_test'] : 0,
                is_numeric($row['avg_character'] ?? null) ? (float) $row['avg_character'] : 0,
                is_numeric($row['avg_memorization'] ?? null) ? (float) $row['avg_memorization'] : 0,
            ]));
        }

        $writer->close();

        return response()->download($filePath, 'rekap-nilai-siswa.xlsx')->deleteFileAfterSend(true);
    }
}
