<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\StudentParent;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    private function getParentProfile(): StudentParent
    {
        return StudentParent::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(): View
    {
        $parent = $this->getParentProfile();
        $children = $parent->students()->with('user')->get();
        $childIds = $children->pluck('id');

        $feedbacks = Feedback::whereIn('student_id', $childIds)
            ->with(['teacher.user', 'student.user', 'subject'])
            ->latest()
            ->paginate(15);

        return view('pages.orangtua.feedbacks.index', compact('feedbacks', 'children'));
    }
}
