<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Siswa\SubmissionRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    private function getStudentProfile(): Student
    {
        return Student::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(): View
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        $assignments = collect();
        if ($classroom) {
            $assignments = Assignment::where('class_id', $classroom->id)
                ->with(['teacher.user', 'subject', 'submissions' => function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                }])
                ->latest()
                ->paginate(10);
        }

        return view('pages.siswa.assignments.index', compact('assignments', 'classroom'));
    }

    public function show(Assignment $assignment): View
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        abort_if(!$classroom || $assignment->class_id !== $classroom->id, 403, 'Anda tidak memiliki akses ke tugas ini.');

        $assignment->load(['teacher.user', 'subject']);
        
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        return view('pages.siswa.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(SubmissionRequest $request, Assignment $assignment): RedirectResponse
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        abort_if(!$classroom || $assignment->class_id !== $classroom->id, 403, 'Anda tidak memiliki akses ke tugas ini.');
        
        // Cek jika sudah deadline
        if (now()->isAfter($assignment->deadline)) {
            return back()->with('error', 'Batas waktu pengumpulan tugas sudah berakhir.');
        }

        // Cek jika sudah pernah mengumpulkan
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->exists();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah pernah mengumpulkan tugas ini.');
        }

        $data = $request->validated();
        $data['assignment_id'] = $assignment->id;
        $data['student_id'] = $student->id;
        
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('assignments/submissions', 'local');
        }

        AssignmentSubmission::create($data);

        return back()->with('success', 'Jawaban tugas Anda berhasil dikumpulkan.');
    }

    public function download(Assignment $assignment)
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        abort_if(!$classroom || $assignment->class_id !== $classroom->id, 403, 'Anda tidak memiliki akses ke tugas ini.');
        
        $path = $assignment->attachment_path;
        
        if (!$path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404, 'File lampiran tidak ditemukan.');
        }
        
        return \Illuminate\Support\Facades\Storage::disk('local')->download($path);
    }

    public function downloadSubmission(Assignment $assignment)
    {
        $student = $this->getStudentProfile();
        $classroom = $student->activeClassroom();

        abort_if(!$classroom || $assignment->class_id !== $classroom->id, 403, 'Anda tidak memiliki akses ke tugas ini.');
        
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->firstOrFail();
            
        $path = $submission->file_path;
        
        if (!$path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404, 'File jawaban Anda tidak ditemukan.');
        }
        
        return \Illuminate\Support\Facades\Storage::disk('local')->download($path);
    }
}
