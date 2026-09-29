<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepalaSekolah\FeedbackRequest;
use App\Models\Feedback;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
        $schoolId = (int) auth()->user()->school_id;

        // Tabel `users` di-exclude dari TenantScope (lihat app/Models/Scopes/TenantScope.php),
        // jadi batasi school_id secara manual ke sekolah kepala sekolah yang login.
        $recipients = User::query()
            ->where('school_id', $schoolId)
            ->whereHas('role')
            ->with('role')
            ->orderBy('name')
            ->get();

        $roleLabels = Role::pluck('display_name', 'name');

        // Daftar lengkap semua user sekolah, dipakai filter dinamis frontend saat Tujuan berubah.
        $recipientOptions = collect([
            ['value' => '', 'label' => '-- Semua (Umum) --', 'role' => null],
        ])->merge($recipients->map(fn (User $u) => [
            'value' => (string) $u->id,
            'label' => $u->name,
            'role' => $u->role->name,
        ]))->values();

        // Setelah validasi gagal, render daftar mengikuti role lama agar state konsisten
        // (penerima yang tidak valid terhadap role tidak ikut ter-render/terpilih).
        $activeRole = old('recipient_role');
        $groups = $recipients
            ->when($activeRole, fn ($c) => $c->filter(fn (User $u) => $u->role->name === $activeRole))
            ->groupBy(fn (User $u) => $u->role->name);

        // Old input recipient_id hanya dipertahankan jika masih valid terhadap daftar
        // yang dirender (role Tujuan lama + sekolah yang sama); selain itu di-reset.
        $visibleIds = $groups->flatten()->pluck('id')->map(static fn ($id) => (int) $id)->all();
        $oldRecipient = old('recipient_id');
        $selectedRecipient = $oldRecipient !== null && in_array((int) $oldRecipient, $visibleIds, true)
            ? (string) $oldRecipient
            : '';

        return view('pages.kepala-sekolah.feedback.create', compact('groups', 'roleLabels', 'recipientOptions', 'selectedRecipient'));
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
}
