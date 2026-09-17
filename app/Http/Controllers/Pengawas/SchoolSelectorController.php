<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SchoolSelectorController extends Controller
{
    /**
     * Tampilkan halaman pilih sekolah.
     */
    public function index(): View
    {
        $user = Auth::user();
        $schools = $user->assignedSchools()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.pengawas.select-school', compact('schools'));
    }

    /**
     * Set sekolah aktif ke dalam session.
     */
    public function setSchool(Request $request): RedirectResponse
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);

        $user = Auth::user();
        
        // Pastikan sekolah memang di-assign ke pengawas ini
        $isAssigned = $user->assignedSchools()->where('schools.id', $request->school_id)->exists();
        
        if (!$isAssigned) {
            return back()->with('error', 'Anda tidak memiliki akses ke sekolah ini.');
        }

        session(['pengawas_school_id' => $request->school_id]);

        return redirect()->route('pengawas.dashboard')->with('success', 'Sekolah aktif berhasil diubah.');
    }
}
