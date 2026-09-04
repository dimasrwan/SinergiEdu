<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Feedback;
use App\Models\LearningMeeting;
use App\Models\Material;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAssessment;
use App\Models\SchoolEvaluation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\WakaFeedback;
use App\Models\ParentSupport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class MonitoringController extends Controller
{
    public function learning(Request $request): View
    {
        $classes = Classroom::orderBy('grade_level')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get()->sortBy('user.name')->values();
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        $filters = $request->validate([
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
        ]);

        $applyFilters = static function ($query) use ($filters): void {
            $query
                ->when($filters['class_id'] ?? null, fn ($builder, $id) => $builder->where('class_id', $id))
                ->when($filters['subject_id'] ?? null, fn ($builder, $id) => $builder->where('subject_id', $id))
                ->when($filters['teacher_id'] ?? null, fn ($builder, $id) => $builder->where('teacher_id', $id))
                ->when($filters['academic_year_id'] ?? null, fn ($builder, $id) => $builder->where('academic_year_id', $id))
                ->when($filters['semester_id'] ?? null, fn ($builder, $id) => $builder->where('semester_id', $id));
        };

        $materialsQuery = Material::query()->with(['teacher.user', 'classroom', 'subject', 'learningMeeting'])->latest();
        $applyFilters($materialsQuery);

        $assignmentsQuery = Assignment::query()
            ->with(['teacher.user', 'classroom', 'subject'])
            ->withCount('submissions')
            ->latest();
        $applyFilters($assignmentsQuery);

        $materials = $materialsQuery->paginate(9, ['*'], 'materials_page')->withQueryString();
        $assignments = $assignmentsQuery->paginate(10, ['*'], 'assignments_page')->withQueryString();
        
        $meetingsQuery = LearningMeeting::query()
            ->with(['teacher.user', 'classroom', 'subject'])
            ->withCount(['materials', 'assessments'])
            ->orderByDesc('meeting_date');
        $applyFilters($meetingsQuery);
        $meetings = $meetingsQuery->paginate(10, ['*'], 'meetings_page')->withQueryString();

        return view('pages.waka.monitoring.learning', compact(
            'classes', 'subjects', 'teachers', 'academicYears', 'semesters', 'filters', 'meetings', 'materials', 'assignments'
        ));
    }

    public function assignment(Assignment $assignment): View
    {
        $assignment->load(['teacher.user', 'classroom', 'subject', 'submissions.student.user']);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $enrolledStudents = $activeYear
            ? $assignment->classroom->students()->wherePivot('academic_year_id', $activeYear->id)->with('user')->get()
            : collect();

        return view('pages.waka.monitoring.assignment', compact('assignment', 'enrolledStudents', 'activeYear'));
    }

    public function classes(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        $classes = Classroom::all()->map(function ($class) use ($activeYear) {
            $studentsCount = $activeYear ? $class->students()->wherePivot('academic_year_id', $activeYear->id)->count() : 0;

            $averageGrade = $activeYear ? StudentAssessment::whereHas('learningMeeting', function ($query) use ($class, $activeYear) {
                $query->where('class_id', $class->id)->where('academic_year_id', $activeYear->id);
            })->get()->avg(fn($a) => $a->average_score) : 0;

            $totalStudents = $studentsCount;
            $totalTasks = Assignment::where('class_id', $class->id)->count();
            $totalSubmissions = AssignmentSubmission::whereHas('assignment', fn($q) => $q->where('class_id', $class->id))->count();
            
            $participationRate = ($totalStudents > 0 && $totalTasks > 0) ? ($totalSubmissions / ($totalStudents * $totalTasks)) * 100 : 0;

            $class->students_count = $studentsCount;
            $class->average_grade = $averageGrade ? round($averageGrade, 2) : 0;
            $class->participation_rate = round($participationRate, 1);

            return $class;
        });

        return view('pages.waka.monitoring.classes', compact('classes', 'activeYear'));
    }

    public function grades(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $classes = Classroom::all();
        $subjects = Subject::all();

        $selectedClassId = $request->query('class_id');
        $selectedSubjectId = $request->query('subject_id');
        $selectedMeetingId = $request->query('meeting_id');

        $avgPreTest = 0; $avgAssignment = 0; $avgPostTest = 0; $avgCharacter = 0; $avgMemorization = 0;
        
        $gradesData = collect();
        $meetings = collect();

        if ($activeYear && $activeSemester) {
            $meetingsQuery = LearningMeeting::where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
                ->orderBy('meeting_date');

            if ($selectedClassId) $meetingsQuery->where('class_id', $selectedClassId);
            if ($selectedSubjectId) $meetingsQuery->where('subject_id', $selectedSubjectId);

            $meetings = $meetingsQuery->get();
            $meetingIds = $meetings->pluck('id');
            if ($selectedMeetingId) {
                abort_unless($meetingIds->contains((int) $selectedMeetingId), 404);
                $meetingIds = collect([(int) $selectedMeetingId]);
            }

            $gradesData = StudentAssessment::whereIn('learning_meeting_id', $meetingIds)
                ->with(['student.user', 'learningMeeting.teacher.user', 'learningMeeting.classroom', 'learningMeeting.subject'])
                ->get();

            if ($gradesData->isNotEmpty()) {
                $avgPreTest = round($gradesData->whereNotNull('pre_test_score')->avg('pre_test_score') ?? 0, 1);
                $avgAssignment = round($gradesData->whereNotNull('assignment_score')->avg('assignment_score') ?? 0, 1);
                $avgPostTest = round($gradesData->whereNotNull('post_test_score')->avg('post_test_score') ?? 0, 1);
                $avgCharacter = round($gradesData->whereNotNull('character_score')->avg('character_score') ?? 0, 1);
                $avgMemorization = round($gradesData->whereNotNull('memorization_score')->avg('memorization_score') ?? 0, 1);
            }
        }

        $subjectComparison = $gradesData
            ->groupBy(fn (StudentAssessment $assessment) => $assessment->learningMeeting->subject->name ?? '-')
            ->map(fn ($assessments, $name) => ['name' => $name, 'avg' => round($assessments->avg('average_score'), 1)])
            ->filter(fn (array $item) => $item['avg'] > 0)
            ->values();

        return view('pages.waka.monitoring.grades', compact(
            'classes', 'subjects', 'selectedClassId', 'selectedSubjectId', 'selectedMeetingId', 'meetings', 'gradesData',
            'avgPreTest', 'avgAssignment', 'avgPostTest', 'avgCharacter', 'avgMemorization',
            'subjectComparison', 'activeYear', 'activeSemester'
        ));
    }

    public function studentProgress(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();
        
        $classes = Classroom::all();
        $students = Student::with(['user', 'classes'])->get()->sortBy('user.name');
        $selectedClassId = $request->query('class_id');
        $selectedStudentId = $request->query('student_id');
        $selectedWeek = $request->query('week_number', 'Minggu 1');

        $studentGrades = collect(); $selectedStudent = null; $wakaFeedback = null; $parentSupport = null;

        if ($selectedStudentId) {
            $selectedStudent = Student::with(['user', 'parent.user'])->find($selectedStudentId);
            if ($selectedStudent && $activeYear) {
                $studentGradesQuery = StudentAssessment::where('student_id', $selectedStudentId)
                    ->with(['learningMeeting.subject', 'learningMeeting.classroom']);

                $studentGradesQuery->whereHas('learningMeeting', function ($query) use ($activeYear, $activeSemester) {
                    $query->where('academic_year_id', $activeYear->id);
                    if ($activeSemester) $query->where('semester_id', $activeSemester->id);
                });

                $studentGrades = $studentGradesQuery->get()->sortBy(fn ($a) => $a->learningMeeting->meeting_date)->values();

                if ($activeSemester) {
                    $wakaFeedback = WakaFeedback::where('student_id', $selectedStudentId)
                        ->where('academic_year_id', $activeYear->id)
                        ->where('semester_id', $activeSemester->id)
                        ->where('week_number', $selectedWeek)
                        ->first();

                    $parentSupport = ParentSupport::where('student_id', $selectedStudentId)
                        ->where('academic_year_id', $activeYear->id)
                        ->where('semester_id', $activeSemester->id)
                        ->where('week_number', $selectedWeek)
                        ->first();
                }

                $teacherFeedbacks = Feedback::where('student_id', $selectedStudentId)
                    ->with(['teacher.user', 'subject'])
                    ->latest()
                    ->get();
            }
        }

        $teacherFeedbacks ??= collect();
        $weeks = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Minggu 6', 'Minggu 7', 'Minggu 8'];

        // Calculate class average for the current subject and meeting
        $classAverage = 0;
        if ($selectedStudent) {
            $classAverage = StudentAssessment::whereHas('learningMeeting', function ($query) use ($selectedStudent, $activeYear) {
                $query->where('academic_year_id', $activeYear->id)
                      ->where('class_id', $selectedStudent->activeClassroom()?->id);
            })->avg('average_score');
        }

        return view('pages.waka.monitoring.student-progress', compact(
            'classes', 'students', 'selectedClassId', 'selectedStudentId', 'studentGrades', 'selectedStudent', 'activeYear', 'activeSemester',
            'selectedWeek', 'wakaFeedback', 'parentSupport', 'teacherFeedbacks', 'weeks', 'classAverage'
        ));
    }

    public function uploadMeetingFile(Request $request, \App\Models\LearningMeeting $meeting)
    {
        $validated = $request->validate([
            'material_file' => 'nullable|file|mimes:pdf,mp4,mov|max:20480',
            'assignment_file' => 'nullable|file|mimes:pdf,docx|max:10240',
        ]);

        if ($request->hasFile('material_file')) {
            $path = $request->file('material_file')->store('materials', 'public');
            $meeting->update(['material_file_path' => $path]);
        }

        if ($request->hasFile('assignment_file')) {
            $path = $request->file('assignment_file')->store('assignments', 'public');
            $meeting->update(['assignment_file_path' => $path]);
        }

        return redirect()->back()->with('success', 'File berhasil diunggah.');
    }

    public function storeCollaborativeAction(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:student_assessments,id',
            'role_type' => 'required|string',
            'feedback_content' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'week_number' => 'required|string',
        ]);

        \App\Models\CollaborativeAction::updateOrCreate(
            [
                'assessment_id' => $validated['assessment_id'],
                'role_type' => $validated['role_type'],
                'week_number' => $validated['week_number'],
            ],
            [
                'user_id' => auth()->id(),
                'feedback_content' => $validated['feedback_content'],
                'action_plan' => $validated['action_plan'],
            ]
        );

        return redirect()->back()->with('success', 'Rencana aksi tersimpan.');
    }

    public function exportExcelKlasikal(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $selectedClassId = $request->query('class_id');
        $selectedSubjectId = $request->query('subject_id');

        $query = StudentAssessment::with(['student.user', 'learningMeeting.classroom', 'learningMeeting.subject']);
        $query->whereHas('learningMeeting', function ($meetingQuery) use ($activeYear, $activeSemester, $selectedClassId, $selectedSubjectId) {
            if ($activeYear) $meetingQuery->where('academic_year_id', $activeYear->id);
            if ($activeSemester) $meetingQuery->where('semester_id', $activeSemester->id);
            if ($selectedClassId) $meetingQuery->where('class_id', $selectedClassId);
            if ($selectedSubjectId) $meetingQuery->where('subject_id', $selectedSubjectId);
        });

        $grades = $query->get();

        $writer = new Writer();
        $filename = "Rekap_Nilai_" . date('Ymd_His') . ".xlsx";
        $writer->openToBrowser($filename);

        $writer->addRow(Row::fromValues(['LAPORAN PENILAIAN KLASIKAL', '', '', '', '', '', '', '', '', '', '']));
        $writer->addRow(Row::fromValues(['Periode', $activeYear->year ?? '-', '', '', '', '', '', '', '', '', '']));
        $writer->addRow(Row::fromValues([]));
        
        $writer->addRow(Row::fromValues([
            'Nama Siswa', 'NIS', 'Kelas', 'Mapel', 'Pertemuan', 'Tanggal',
            'Pre-Test', 'Tugas', 'Post-Test', 'Karakter', 'Hafalan', 'Juz', 'Ayat', 'Rata-Rata'
        ]));

        foreach ($grades as $grade) {
            $writer->addRow(Row::fromValues([
                $grade->student->user->name ?? '-',
                $grade->student->nis ?? '-',
                $grade->learningMeeting->classroom->name ?? '-',
                $grade->learningMeeting->subject->name ?? '-',
                $grade->learningMeeting->meeting_number ?? '-',
                $grade->learningMeeting->meeting_date?->format('d-m-Y') ?? '-',
                $grade->pre_test_score ?? 0,
                $grade->assignment_score ?? 0,
                $grade->post_test_score ?? 0,
                $grade->character_score ?? 0,
                $grade->memorization_score ?? 0,
                $grade->memorization_juz ?? '-',
                $grade->memorization_ayat ?? '-',
                $grade->average_score
            ]));
        }

        $writer->close();
    }

    public function exportIndividual(Request $request)
    {
        $studentId = $request->query('student_id');
        if (!$studentId) return redirect()->back()->with('error', 'Siswa belum dipilih.');

        $activeYear = AcademicYear::where('is_active', true)->first();
        $student = Student::with('user')->find($studentId);

        if (!$student) return redirect()->back()->with('error', 'Siswa tidak ditemukan.');

        $query = StudentAssessment::where('student_id', $studentId)
            ->with(['learningMeeting.subject', 'learningMeeting.classroom']);
        $query->whereHas('learningMeeting', function ($meetingQuery) use ($activeYear) {
            if ($activeYear) $meetingQuery->where('academic_year_id', $activeYear->id);
        });
        $grades = $query->get()->sortBy(fn ($a) => $a->learningMeeting->meeting_date);

        $filename = "Laporan_Perkembangan_" . str_replace(' ', '_', $student->user->name) . "_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($student, $grades, $activeYear) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); 

            fputcsv($file, ['LAPORAN PERKEMBANGAN HASIL BELAJAR SISWA']);
            fputcsv($file, ['Nama Siswa', $student->user->name]);
            fputcsv($file, ['NIS', $student->nis ?? '-']);
            fputcsv($file, ['Tahun Ajaran', $activeYear->year ?? '-']);
            fputcsv($file, []);
            fputcsv($file, [
                'Mata Pelajaran', 'Pertemuan', 'Tanggal', 'Nilai Tes Awal', 'Nilai Tugas',
                'Nilai Tes Akhir', 'Nilai Karakter', 'Nilai Hafalan',
                'Rata-Rata', 'Catatan Guru'
            ]);

            foreach ($grades as $grade) {
                fputcsv($file, [
                    $grade->learningMeeting->subject->name ?? '-',
                    $grade->learningMeeting->meeting_number ?? '-',
                    $grade->learningMeeting->meeting_date?->format('d-m-Y') ?? '-',
                    $grade->pre_test_score ?? '-',
                    $grade->assignment_score ?? '-',
                    $grade->post_test_score ?? '-',
                    $grade->character_score ?? '-',
                    $grade->memorization_score ?? '-',
                    $grade->average_score,
                    $grade->notes ?? '-'
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportPdfKlasikal(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedClassId = $request->query('class_id');
        
        $grades = StudentAssessment::with(['student.user', 'learningMeeting.classroom', 'learningMeeting.subject'])
            ->whereHas('learningMeeting', function ($q) use ($selectedClassId) {
                if ($selectedClassId) $q->where('class_id', $selectedClassId);
            })
            ->get();

        $pdf = PdfFacade::loadView('pages.waka.monitoring.pdf-klasikal', compact('grades', 'activeYear'));
        return $pdf->download("Laporan_Klasikal_" . date('Ymd_His') . ".pdf");
    }

    public function evaluations(): View
    {
        $evaluations = SchoolEvaluation::with('user')->latest()->paginate(10);
        return view('pages.waka.monitoring.evaluations', compact('evaluations'));
    }
}
