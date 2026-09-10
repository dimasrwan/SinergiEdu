<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Admin.
     */
    public function index(): View
    {
        // 1. Context Akademik
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = $activeAcademicYear 
            ? Semester::where('academic_year_id', $activeAcademicYear->id)->where('is_active', true)->first()
            : null;

        $missingContext = !$activeAcademicYear || !$activeSemester;

        $unplacedStudents = 0;
        $unassignedTeachers = 0;

        if (!$missingContext) {
            $unplacedStudents = Student::whereDoesntHave('classes', function ($q) use ($activeAcademicYear) {
                $q->where('student_classes.academic_year_id', $activeAcademicYear->id);
            })->count();

            $unassignedTeachers = Teacher::whereNotIn('id', function($q) use ($activeAcademicYear, $activeSemester) {
                $q->select('teacher_id')
                  ->from('teacher_subjects')
                  ->where('academic_year_id', $activeAcademicYear->id)
                  ->where('semester_id', $activeSemester->id);
            })->count();
        }

        // 2. KPI Metrics
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();
        $totalParents = StudentParent::count();
        
        $classroomsQuery = Classroom::query();
        if ($activeAcademicYear) {
            $classroomsQuery->where('academic_year_id', $activeAcademicYear->id);
        }
        $totalClasses = $classroomsQuery->count();
        
        // Context classes string (SD, SMP, SMA)
        $educationLevels = $classroomsQuery->distinct('education_level')->pluck('education_level')->toArray();
        $educationLevelsString = count($educationLevels) > 0 ? 'Tersebar di tingkat ' . implode(', ', $educationLevels) : 'Belum ada kelas';

        // 3. User Growth (Last 6 Months)
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $growthData = User::select(
                DB::raw('COUNT(id) as total'), 
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year')
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
            
        $growthLabels = [];
        $growthValues = [];
        // Accumulate data for the last 6 months to make a line chart
        $runningTotal = User::where('created_at', '<', $sixMonthsAgo)->count();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $monthsIndo = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
            $monthShort = $monthsIndo[$month];
            
            // find if we have data for this month
            $match = $growthData->first(function($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });
            
            $added = $match ? $match->total : 0;
            $runningTotal += $added;
            
            $growthLabels[] = $monthShort;
            $growthValues[] = $runningTotal;
        }

        // 4. User Distribution
        $distributionData = DB::table('users')
            ->where('users.school_id', app(\App\Services\TenantService::class)->getSchoolId())
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('COUNT(users.id) as total'))
            ->groupBy('roles.id', 'roles.name')
            ->get();

        $recentUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach($recentUsers as $user) {
            $user->role_model_id = null;
            $r = strtolower($user->role?->name ?? '');
            if ($r === 'guru') {
                $user->role_model_id = \App\Models\Teacher::where('user_id', $user->id)->value('id');
            } elseif ($r === 'siswa') {
                $user->role_model_id = \App\Models\Student::where('user_id', $user->id)->value('id');
            } elseif ($r === 'orang tua') {
                $user->role_model_id = \App\Models\StudentParent::where('user_id', $user->id)->value('id');
            } elseif ($r === 'waka kurikulum' || $r === 'waka') {
                $user->role_model_id = \App\Models\Waka::where('user_id', $user->id)->value('id');
            } elseif ($r === 'pengawas') {
                $user->role_model_id = \App\Models\Pengawas::where('user_id', $user->id)->value('id');
            } elseif ($r === 'kepala sekolah') {
                $user->role_model_id = \App\Models\KepalaSekolah::where('user_id', $user->id)->value('id');
            }
        }

        return view('pages.admin.dashboard', compact(
            'activeAcademicYear',
            'activeSemester',
            'totalTeachers',
            'totalStudents',
            'totalParents',
            'totalClasses',
            'educationLevelsString',
            'growthLabels',
            'growthValues',
            'distributionData',
            'recentUsers',
            'missingContext',
            'unplacedStudents',
            'unassignedTeachers'
        ));
    }
}
