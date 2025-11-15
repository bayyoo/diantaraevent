<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class VerifyOTPController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function create()
    {
        return view('auth.verify-otp');
    }

    /**
     * Handle OTP verification request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        // Use verification_email from session, fallback to manual_email
        $email = $request->email ?: session('verification_email') ?: $request->manual_email;
        
        if (!$email) {
            throw ValidationException::withMessages([
                'email' => ['Email harus diisi.'],
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        // Check if OTP code matches
        if ($user->otp_code !== $request->otp_code) {
            throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP tidak valid.'],
            ]);
        }

        // Check if OTP has expired
        if (!$user->otp_expires_at || Carbon::now()->isAfter($user->otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp_code' => ['Kode OTP telah kedaluwarsa. Silakan minta kode baru.'],
            ]);
        }

        // OTP is valid, verify the email
        $user->update([
            'email_verified_at' => Carbon::now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Clear verification email from session
        session()->forget('verification_email');

        // Log the user in
        Auth::login($user);

        return redirect('/home')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }

    /**
     * Resend OTP code to user's email.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan.'
                ], 404);
            }
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        // Check if user is already verified
        if ($user->email_verified_at) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah terverifikasi. Silakan login.'
                ], 400);
            }
            return redirect()->route('login')
                ->with('status', 'Email sudah terverifikasi. Silakan login.');
        }

        // Generate new OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpiresAt = Carbon::now()->addMinutes(15);

        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        // Send new OTP email
        \Mail::to($user->email)->send(new \App\Mail\VerifyEmailOTP($user, $otpCode));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim ke email Anda.'
            ]);
        }

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
