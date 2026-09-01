<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\SchoolEvaluation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $evaluations = SchoolEvaluation::where('user_id', auth()->id())
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.pengawas.evaluations.index', compact('evaluations', 'search'));
    }

    public function show(SchoolEvaluation $evaluation): View
    {
        abort_if($evaluation->user_id !== auth()->id(), 403, 'Akses ditolak.');
        return view('pages.pengawas.evaluations.show', compact('evaluation'));
    }

    public function create(): View
    {
        return view('pages.pengawas.evaluations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
        ], [
            'title.required' => 'Judul evaluasi wajib diisi.',
            'content.required' => 'Detail evaluasi wajib diisi.',
        ]);

        $data['user_id'] = auth()->id();

        SchoolEvaluation::create($data);

        return redirect()->route('pengawas.evaluations.index')->with('success', 'Evaluasi sekolah berhasil dikirim.');
    }

    public function edit(SchoolEvaluation $evaluation): View
    {
        abort_if($evaluation->user_id !== auth()->id(), 403, 'Akses ditolak.');
        return view('pages.pengawas.evaluations.edit', compact('evaluation'));
    }

    public function update(Request $request, SchoolEvaluation $evaluation): RedirectResponse
    {
        abort_if($evaluation->user_id !== auth()->id(), 403, 'Akses ditolak.');

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $evaluation->update($data);

        return redirect()->route('pengawas.evaluations.index')->with('success', 'Evaluasi sekolah berhasil diperbarui.');
    }

    public function destroy(SchoolEvaluation $evaluation): RedirectResponse
    {
        abort_if($evaluation->user_id !== auth()->id(), 403, 'Akses ditolak.');

        $evaluation->delete();

        return redirect()->route('pengawas.evaluations.index')->with('success', 'Evaluasi sekolah berhasil dihapus.');
    }
}
