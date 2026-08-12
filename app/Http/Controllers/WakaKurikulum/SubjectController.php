<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Http\Requests\WakaKurikulum\SubjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::latest()->paginate(10);
        return view('pages.waka.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('pages.waka.subjects.create');
    }

    public function store(SubjectRequest $request): RedirectResponse
    {
        Subject::create($request->validated());
        return redirect()->route('waka.subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject): View
    {
        return view('pages.waka.subjects.edit', compact('subject'));
    }

    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());
        return redirect()->route('waka.subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();
        return redirect()->route('waka.subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
