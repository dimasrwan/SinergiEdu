<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = $request->user();
        $oldPhoto = $user->profile_photo_path;

        $file = $request->file('photo');
        $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile-photos', $filename, 'public');

        if ($path) {
            $user->profile_photo_path = $path;
            
            try {
                $user->save();
                
                if ($oldPhoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldPhoto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPhoto);
                }
            } catch (\Exception $e) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
                throw $e;
            }
        }

        return Redirect::back()->with('status', 'profile-photo-updated')->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Delete the user's profile photo.
     */
    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = null;
        $user->save();

        return Redirect::back()->with('status', 'profile-photo-deleted')->with('success', 'Foto profil berhasil dihapus.');
    }
}
