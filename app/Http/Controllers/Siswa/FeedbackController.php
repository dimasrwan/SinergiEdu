<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\ParentSupport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    use Concerns\HasStudentProfile;

    public function index(Request $request): View
    {
        $student = $this->requireStudentProfile();
        $tab = $request->query('tab', 'guru');
        if (!in_array($tab, ['guru', 'orangtua'], true)) {
            $tab = 'guru';
        }

        $feedbacks = Feedback::where('student_id', $student->id)
            ->with(['teacher.user', 'subject'])
            ->latest()
            ->paginate(10, ['*'], 'feedback_page');

        $parentSupports = ParentSupport::where('student_id', $student->id)
            ->with(['academicYear', 'semester'])
            ->latest()
            ->paginate(10, ['*'], 'support_page');

        $totalFeedbacks = $feedbacks->total();
        $totalSupports = $parentSupports->total();

        return view('pages.siswa.feedbacks.index', compact(
            'feedbacks',
            'parentSupports',
            'tab',
            'totalFeedbacks',
            'totalSupports'
        ));
    }

    public function show(Feedback $feedback): View
    {
        $student = $this->requireStudentProfile();
        abort_if($feedback->student_id !== $student->id, 403, 'Akses ditolak.');

        $feedback->load(['teacher.user', 'subject']);

        return view('pages.siswa.feedbacks.show', compact('feedback'));
    }
}
