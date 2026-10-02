<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepalaSekolah\ActionPlanRequest;
use App\Models\Role;
use App\Models\SchoolActionPlan;
use App\Models\User;
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
        $schoolId = (int) auth()->user()->school_id;

        // Tabel `users` di-exclude dari TenantScope (lihat app/Models/Scopes/TenantScope.php),
        // jadi batasi school_id secara manual ke sekolah kepala sekolah yang login.
        $targets = User::query()
            ->where('school_id', $schoolId)
            ->whereHas('role')
            ->with('role')
            ->orderBy('name')
            ->get();

        $roleLabels = Role::pluck('display_name', 'name');

        // Daftar lengkap semua user sekolah, dipakai filter dinamis frontend saat Target Role berubah.
        $targetOptions = collect([
            ['value' => '', 'label' => '-- Semua sesuai role --', 'role' => null],
        ])->merge($targets->map(fn (User $u) => [
            'value' => (string) $u->id,
            'label' => $u->name,
            'role' => $u->role->name,
        ]))->values();

        // Target Orang hanya dirender setelah Target Role dipilih (placeholder sampai
        // role dipilih — tidak menampilkan seluruh user). Setelah validasi gagal,
        // daftar mengikuti Target Role lama agar state konsisten.
        $activeRole = old('target_role') ?: null;
        $groups = $targets
            ->filter(fn (User $u) => $activeRole !== null && $u->role->name === $activeRole)
            ->groupBy(fn (User $u) => $u->role->name);

        // Old input target_user_id hanya dipertahankan jika masih valid terhadap daftar
        // yang dirender (Target Role lama + sekolah yang sama); selain itu di-reset.
        $visibleIds = $groups->flatten()->pluck('id')->map(static fn ($id) => (int) $id)->all();
        $oldTarget = old('target_user_id');
        $selectedTarget = $oldTarget !== null && in_array((int) $oldTarget, $visibleIds, true)
            ? (string) $oldTarget
            : '';

        return view('pages.kepala-sekolah.rencana-aksi.create', compact('groups', 'roleLabels', 'targetOptions', 'selectedTarget'));
    }

    public function store(ActionPlanRequest $request): RedirectResponse
    {
        SchoolActionPlan::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            // Kolom nullable: '' (placeholder '-- Semua sesuai role --' / opsi kosong) disimpan sebagai null.
            'target_role' => $request->target_role ?: null,
            'target_user_id' => $request->target_user_id ?: null,
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
