<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ResendMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request): View
    {
        return view('auth.forgot-password', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Check if user exists first
            $user = \App\Models\User::where('email', $request->email)->first();
            
            if (!$user) {
                \Log::info('Password reset attempted for non-existent email: ' . $request->email);
                return back()->with('status', 'Link reset password telah dikirim ke email Anda jika akun tersebut terdaftar.');
            }

            // Generate password reset token using the broker
            $token = Password::createToken($user);

            // Build the reset URL (same as Laravel default)
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ], false));

            // Send reset link email via Resend API
            $sent = app(ResendMailer::class)->sendPasswordReset($user->email, $resetUrl, $user->name);

            if ($sent) {
                \Log::info('Password reset link sent successfully via Resend to: ' . $request->email);
                return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            }

            \Log::error('Failed to send password reset link via Resend to: ' . $request->email);
            return back()->with('error', 'Gagal mengirim link reset password. Silakan coba lagi atau hubungi administrator.');

        } catch (\Exception $e) {
            \Log::error('Password reset error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Terjadi kesalahan saat mengirim email reset password. Silakan coba lagi atau hubungi administrator.');
        }
    }
}
