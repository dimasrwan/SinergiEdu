<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', \App\Models\Setting::class);
        $setting = Setting::first() ?? new Setting();
        return view('pages.admin.settings.index', compact('setting'));
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $setting = Setting::first() ?? new Setting();
        
        $setting->school_name = $request->school_name;
        $setting->school_npsn = $request->school_npsn;
        $setting->school_address = $request->school_address;
        $setting->school_phone = $request->school_phone;
        $setting->school_email = $request->school_email;

        if ($request->hasFile('school_logo')) {
            // Delete old logo if exists
            if ($setting->school_logo && Storage::disk('public')->exists($setting->school_logo)) {
                Storage::disk('public')->delete($setting->school_logo);
            }
            
            $path = $request->file('school_logo')->store('logos', 'public');
            $setting->school_logo = $path;
        }

        $setting->save();

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
