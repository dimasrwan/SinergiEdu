<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);
        $school = Auth::user()->school;
        return view('pages.admin.settings.index', compact('school'));
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $school = Auth::user()->school;
        
        $school->name = $request->school_name;
        $school->npsn = $request->school_npsn;
        $school->address = $request->school_address;
        $school->phone = $request->school_phone;
        $school->email = $request->school_email;

        if ($request->hasFile('school_logo')) {
            // Delete old logo if exists
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }
            
            $path = $request->file('school_logo')->store('logos', 'public');
            $school->logo = $path;
        }

        $school->save();

        return redirect()->route('admin.settings.index')->with('success', 'Profil Sekolah berhasil diperbarui.');
    }
}
