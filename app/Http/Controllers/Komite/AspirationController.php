<?php

declare(strict_types=1);

namespace App\Http\Controllers\Komite;

use App\Http\Controllers\Controller;
use App\Models\KomiteAspiration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AspirationController extends Controller
{
    public function index(): View
    {
        $aspirations = KomiteAspiration::where('user_id', auth()->id())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.komite.aspirations.index', compact('aspirations'));
    }

    public function create(): View
    {
        return view('pages.komite.aspirations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
        ]);

        KomiteAspiration::create([
            'school_id' => auth()->user()->school_id,
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'status' => 'pending',
        ]);

        return redirect()->route('komite.aspirations.index')
            ->with('success', 'Aspirasi Komite Sekolah berhasil dikirimkan.');
    }

    public function show(KomiteAspiration $aspiration): View
    {
        // Enforce tenant isolation & ownership/access check
        if ($aspiration->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki hak akses ke data aspirasi ini.');
        }

        return view('pages.komite.aspirations.show', compact('aspiration'));
    }
}
