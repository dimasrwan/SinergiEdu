<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\SchoolEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function index(): View
    {
        $evaluations = SchoolEvaluation::with('user')->latest()->paginate(15);

        return view('pages.kepala-sekolah.evaluasi.index', compact('evaluations'));
    }

    public function create(): View
    {
        return view('pages.kepala-sekolah.evaluasi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
        ]);

        SchoolEvaluation::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('kepala-sekolah.evaluasi.index')
            ->with('success', 'Evaluasi sekolah berhasil disimpan.');
    }

    public function show(SchoolEvaluation $evaluation): View
    {
        $evaluation->load('user');
        return view('pages.kepala-sekolah.evaluasi.show', compact('evaluation'));
    }
}
