<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmailOTP;
use App\Rules\Recaptcha;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'education' => 'required|string|in:SD,SMP,SMA/SMK,D3,S1,S2,S3',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Temporarily disable reCAPTCHA for development
            // 'g-recaptcha-response' => ['required', new Recaptcha],
        ]);

        // Generate OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpires = Carbon::now()->addMinutes(15);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'education' => $request->education,
            'password' => Hash::make($request->password),
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpires,
        ]);

        event(new Registered($user));

        // Send OTP email
        Mail::to($user->email)->send(new VerifyEmailOTP($user, $otpCode));
        
        // Store email in session for OTP verification
        session(['verification_email' => $user->email]);
        
        return redirect()->route('verify.otp')->with([
            'success' => 'Registrasi berhasil! Silakan cek email Anda untuk kode OTP.',
            'email' => $user->email
        ]);
    }
}
