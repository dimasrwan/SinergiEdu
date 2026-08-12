<?php

declare(strict_types=1);

namespace App\Http\Controllers\WakaKurikulum;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Http\Requests\WakaKurikulum\ClassroomRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function index(): View
    {
        $classes = Classroom::latest()->paginate(10);
        return view('pages.waka.classes.index', compact('classes'));
    }

    public function create(): View
    {
        return view('pages.waka.classes.create');
    }

    public function store(ClassroomRequest $request): RedirectResponse
    {
        Classroom::create($request->validated());
        return redirect()->route('waka.classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Classroom $class): View
    {
        // Parameter binding menggunakan $class dari rute
        return view('pages.waka.classes.edit', compact('class'));
    }

    public function update(ClassroomRequest $request, Classroom $class): RedirectResponse
    {
        $class->update($request->validated());
        return redirect()->route('waka.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $class): RedirectResponse
    {
        $class->delete();
        return redirect()->route('waka.classes.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
