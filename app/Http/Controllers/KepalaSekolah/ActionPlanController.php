<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepalaSekolah\ActionPlanRequest;
use App\Models\SchoolActionPlan;
use App\Models\Pengawas;
use App\Models\Teacher;
use App\Models\Waka;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActionPlanController extends Controller
{
    public function index(): View
    {
        $actionPlans = SchoolActionPlan::with(['creator', 'target'])->latest()->get();

        $draf = $actionPlans->where('status', 'draft');
        $inProgress = $actionPlans->where('status', 'in_progress');
        $completed = $actionPlans->where('status', 'completed');
        $cancelled = $actionPlans->where('status', 'cancelled');

        return view('pages.kepala-sekolah.rencana-aksi.index', compact(
            'actionPlans', 'draf', 'inProgress', 'completed', 'cancelled'
        ));
    }

    public function create(): View
    {
        $targets = [
            'guru' => Teacher::with('user')->get()->map(fn ($t) => ['id' => $t->user_id, 'label' => $t->user->name]),
            'waka' => Waka::with('user')->get()->map(fn ($w) => ['id' => $w->user_id, 'label' => $w->user->name]),
            'pengawas' => Pengawas::with('user')->get()->map(fn ($p) => ['id' => $p->user_id, 'label' => $p->user->name]),
        ];

        return view('pages.kepala-sekolah.rencana-aksi.create', compact('targets'));
    }

    public function store(ActionPlanRequest $request): RedirectResponse
    {
        SchoolActionPlan::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'target_role' => $request->target_role,
            'target_user_id' => $request->target_user_id,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status ?? 'draft',
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('kepala-sekolah.rencana-aksi.index')
            ->with('success', 'Rencana aksi berhasil dibuat.');
    }

    public function show(SchoolActionPlan $rencana_aksi): View
    {
        $actionPlan = $rencana_aksi;
        $actionPlan->load(['creator', 'target']);
        return view('pages.kepala-sekolah.rencana-aksi.show', compact('actionPlan'));
    }

    public function updateStatus(Request $request, SchoolActionPlan $actionPlan): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:draft,in_progress,completed,cancelled',
        ]);

        $actionPlan->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : $actionPlan->completed_at,
        ]);

        return back()->with('success', 'Status rencana aksi diperbarui.');
    }

    public function destroy(SchoolActionPlan $rencana_aksi): RedirectResponse
    {
        $rencana_aksi->delete();
        return redirect()->route('kepala-sekolah.rencana-aksi.index')
            ->with('success', 'Rencana aksi dihapus.');
    }
}
