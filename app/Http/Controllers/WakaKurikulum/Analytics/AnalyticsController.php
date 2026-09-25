<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum\Analytics;

use App\Http\Controllers\Controller;
use App\Models\StudentAssessment;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        // Data Dummy untuk Analitik (akan diganti query nyata nanti)
        $performanceTrend = [85, 87, 86, 89, 90, 88, 91];
        $subjectComparison = [
            ['name' => 'Matematika', 'avg' => 88.5],
            ['name' => 'Fisika', 'avg' => 85.2],
            ['name' => 'Biologi', 'avg' => 90.1],
        ];
        $riskyStudents = [
            ['name' => 'Budi', 'class' => 'XI IPA 1', 'score' => 65],
            ['name' => 'Ani', 'class' => 'XI IPS 2', 'score' => 68],
        ];

        return view('pages.waka.analytics.index', compact('performanceTrend', 'subjectComparison', 'riskyStudents'));
    }
}
