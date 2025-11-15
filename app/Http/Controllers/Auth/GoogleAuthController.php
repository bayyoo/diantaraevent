<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user already exists by email
            $user = User::where('email', $googleUser->email)->first();
            
            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                }
                
                // Login existing user
                Auth::login($user);
                
                return redirect()->intended(route('home'))->with('success', 'Berhasil login dengan Google!');
            } else {
                // Create new user from Google
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(), // Auto verify email for Google users
                    'password' => Hash::make(Str::random(24)), // Random password
                    'is_admin' => false,
                ]);
                
                // Login new user
                Auth::login($user);
                
                return redirect()->route('home')->with('success', 'Akun berhasil dibuat dengan Google! Selamat datang di Diantara.');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi atau gunakan metode login lain.');
        }
    }
}
