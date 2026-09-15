<?php

declare(strict_types=1);

namespace App\Http\Controllers\Komite;

use App\Http\Controllers\Controller;
use App\Models\SchoolActionPlan;
use Illuminate\View\View;

class SchoolProgramController extends Controller
{
    public function index(): View
    {
        $school = auth()->user()->school;
        $programs = SchoolActionPlan::latest()->paginate(10)->withQueryString();

        return view('pages.komite.school-programs.index', compact('school', 'programs'));
    }
}
