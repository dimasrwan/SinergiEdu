<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPreference;

class UserSettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Load default preference if not exists
        if (!$user->preferences) {
            $user->preferences()->create([
                'theme' => 'system',
                'email_notifications' => true,
                'push_notifications' => true,
            ]);
            $user->refresh();
        }

        $preferences = $user->preferences;

        return view('settings.index', compact('user', 'preferences'));
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'theme' => ['required', 'string', 'in:light,dark,system'],
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
        ]);

        $user = Auth::user();
        
        $preferencesData = [
            'theme' => $validated['theme'],
            'email_notifications' => $request->has('email_notifications'),
            'push_notifications' => $request->has('push_notifications'),
        ];

        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            $preferencesData
        );

        return redirect()->route('settings.index')->with('success', 'Preferensi berhasil diperbarui.');
    }
}
