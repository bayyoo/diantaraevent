<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyController extends Controller
{
    /**
     * Display the privacy settings form.
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('settings.privacy', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's privacy settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'profile_public' => 'boolean',
            'show_profile_in_events' => 'boolean',
        ]);
        
        $user->update([
            'profile_public' => $request->has('profile_public'),
            'show_profile_in_events' => $request->has('show_profile_in_events'),
        ]);
        
        return redirect()->route('privacy.edit')
            ->with('success', 'Pengaturan privasi berhasil diperbarui');
    }
}
