<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Waka Kurikulum.
     */
    public function index(): View
    {
        return view('pages.waka.dashboard');
    }
}
