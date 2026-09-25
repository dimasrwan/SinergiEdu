<?php

declare(strict_types=1);

namespace App\Http\Controllers\Komite;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SchoolProfileController extends Controller
{
    public function index(): View
    {
        $school = auth()->user()->school;

        return view('pages.komite.school-profile.index', compact('school'));
    }
}
