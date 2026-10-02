<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepalaSekolah\ActionPlanRequest;
use App\Models\SchoolActionPlan;
use App\Services\RencanaAksiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RencanaAksiController extends Controller
{
    private const PREFIX = 'waka.rencana-aksi';

    public function index(): View
    {
        $actionPlans = RencanaAksiService::visiblePlans(auth()->user())
            ->with(['creator', 'target'])
            ->latest()
            ->get();

        return view('pages.rencana-aksi.index', [
            'prefix' => self::PREFIX,
            'actionPlans' => $actionPlans,
            'canCreate' => true,
            'pageDescription' => 'Tindak lanjut dari kepala sekolah untuk guru.',
        ]);
    }

    public function create(): View
    {
        $allowedRoles = RencanaAksiService::allowedTargetRoles(auth()->user());

        return view('pages.rencana-aksi.create', array_merge([
            'prefix' => self::PREFIX,
            'targetRoleOptions' => RencanaAksiService::targetRoleOptions($allowedRoles),
        ], RencanaAksiService::createPayload($allowedRoles)));
    }

    public function store(ActionPlanRequest $request): RedirectResponse
    {
        RencanaAksiService::storePlan($request->validated());

        return redirect()->route(self::PREFIX.'.index')
            ->with('success', 'Rencana aksi berhasil dibuat.');
    }

    public function show(SchoolActionPlan $rencana_aksi): View
    {
        abort_unless(
            RencanaAksiService::visiblePlans(auth()->user())->whereKey($rencana_aksi->getKey())->exists(),
            404
        );

        $rencana_aksi->load(['creator', 'target']);

        return view('pages.rencana-aksi.show', [
            'prefix' => self::PREFIX,
            'actionPlan' => $rencana_aksi,
        ]);
    }
}
