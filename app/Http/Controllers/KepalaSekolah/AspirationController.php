<?php

declare(strict_types=1);

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\KomiteAspiration;
use App\Models\KomiteAspirationResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AspirationController extends Controller
{
    public function index(): View
    {
        $aspirations = KomiteAspiration::where('school_id', auth()->user()->school_id)
            ->latest()
            ->paginate(15);

        return view('pages.kepala-sekolah.aspirations.index', compact('aspirations'));
    }

    public function show(KomiteAspiration $aspiration): View
    {
        abort_if($aspiration->school_id !== auth()->user()->school_id, 403);
        $aspiration->load(['user', 'responses.user']);
        return view('pages.kepala-sekolah.aspirations.show', compact('aspiration'));
    }

    public function storeResponse(Request $request, KomiteAspiration $aspiration): RedirectResponse
    {
        abort_if($aspiration->school_id !== auth()->user()->school_id, 403);

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        KomiteAspirationResponse::create([
            'komite_aspiration_id' => $aspiration->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $aspiration->update(['status' => 'reviewed']);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}
