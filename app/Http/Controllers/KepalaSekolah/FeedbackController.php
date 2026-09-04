<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepalaSekolah\FeedbackRequest;
use App\Models\Feedback;
use App\Models\Pengawas;
use App\Models\Teacher;
use App\Models\Waka;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        $feedbacks = Feedback::where('sender_id', auth()->id())
            ->orWhere('recipient_id', auth()->id())
            ->with(['sender', 'recipient'])
            ->latest()
            ->paginate(15);

        return view('pages.kepala-sekolah.feedback.index', compact('feedbacks'));
    }

    public function create(): View
    {
        $teachers = Teacher::with('user')->get()->map(fn ($t) => [
            'id' => $t->user_id,
            'label' => $t->user->name,
        ]);

        $wakas = Waka::with('user')->get()->map(fn ($w) => [
            'id' => $w->user_id,
            'label' => $w->user->name,
        ]);

        $pengawas = Pengawas::with('user')->get()->map(fn ($p) => [
            'id' => $p->user_id,
            'label' => $p->user->name,
        ]);

        return view('pages.kepala-sekolah.feedback.create', compact('teachers', 'wakas', 'pengawas'));
    }

    public function store(FeedbackRequest $request): RedirectResponse
    {
        $teacherId = null;
        if ($request->recipient_role === 'guru' && $request->recipient_id) {
            $teacherId = Teacher::where('user_id', $request->recipient_id)->value('id');
        }

        Feedback::create([
            'sender_id' => auth()->id(),
            'recipient_role' => $request->recipient_role,
            'recipient_id' => $request->recipient_id,
            'teacher_id' => $teacherId,
            'type' => 'neutral',
            'category' => $request->category,
            'priority' => $request->priority,
            'title' => $request->title,
            'message' => $request->message,
            'status' => 'sent',
            'action_plan' => $request->action_plan,
            'action_deadline' => $request->action_deadline,
        ]);

        return redirect()->route('kepala-sekolah.feedback.index')
            ->with('success', 'Feedback strategis berhasil dikirim.');
    }

    public function show(Feedback $feedback): View
    {
        $feedback->load(['sender', 'recipient']);

        return view('pages.kepala-sekolah.feedback.show', compact('feedback'));
    }

    public function updateStatus(Request $request, Feedback $feedback): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:draft,sent,acknowledged,actioned',
        ]);

        $feedback->update(['status' => $request->status]);

        return back()->with('success', 'Status feedback diperbarui.');
    }
}
