<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Siswa.
     */
    public function index(): View
    {
        return view('pages.siswa.dashboard');
    }
}
