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

        $oldLogo = $school->logo;
        $newLogoPath = null;
        if ($request->hasFile('school_logo')) {
            $file = $request->file('school_logo');
            $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
            $newLogoPath = $file->storeAs('schools/logos', $filename, 'public');
            
            if ($newLogoPath) {
                $school->logo = $newLogoPath;
            }
        }

        try {
            $school->save();
            
            if ($request->hasFile('school_logo') && $oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
        } catch (\Exception $e) {
            if ($newLogoPath && Storage::disk('public')->exists($newLogoPath)) {
                Storage::disk('public')->delete($newLogoPath);
            }
            throw $e;
        }

        return redirect()->route('admin.settings.index')->with('success', 'Profil Sekolah berhasil diperbarui.');
    }
}
