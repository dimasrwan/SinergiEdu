<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\SchoolActionPlan;
use App\Services\RencanaAksiService;
use Illuminate\View\View;

/**
 * Rencana Aksi siswa: READ-ONLY.
 * Hanya rencana yang ditujukan kepadanya (target_user_id) atau ke seluruh siswa.
 * Tidak ada route create/store/edit/delete untuk siswa.
 */
class RencanaAksiController extends Controller
{
    private const PREFIX = 'siswa.rencana-aksi';

    public function index(): View
    {
        $actionPlans = RencanaAksiService::visiblePlans(auth()->user())
            ->with(['creator', 'target'])
            ->latest()
            ->get();

        return view('pages.rencana-aksi.index', [
            'prefix' => self::PREFIX,
            'actionPlans' => $actionPlans,
            'canCreate' => false,
            'pageDescription' => 'Rencana aksi yang ditujukan untuk Anda.',
        ]);
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
