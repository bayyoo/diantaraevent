<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PartnerAuthController extends Controller
{
    /**
     * Show partner login form
     */
    public function showLoginForm()
    {
        return view('partner.auth.login');
    }

    /**
     * Handle partner login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('partner')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $partner = Auth::guard('partner')->user();
            
            if (!$partner->isVerified()) {
                Auth::guard('partner')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum diverifikasi. Silakan periksa email untuk verifikasi.',
                ]);
            }

            return redirect()->intended(route('diantaranexus.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak sesuai dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Show partner registration form
     */
    public function showRegistrationForm()
    {
        // Force logout any authenticated partner
        if (Auth::guard('partner')->check()) {
            Auth::guard('partner')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        
        return view('partner.auth.register');
    }

    /**
     * Handle partner registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:partners',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'organization_name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store registration data in session
        $request->session()->put('partner_registration', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'organization_name' => $request->organization_name,
            'address' => $request->address,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP email
        try {
            Mail::send('emails.partner-otp', ['otp' => $otp, 'name' => $request->name], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Verifikasi Akun Partner Nexus - Kode OTP');
            });

            \Log::info('[Partner OTP] Sent OTP for registration', [
                'email' => $request->email,
                'otp' => $otp,
                'expires_at' => $request->session()->get('partner_registration.otp_expires_at')
            ]);

            return redirect()->route('diantaranexus.verify-otp')->with('success', 
                'Kode verifikasi telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi.'])->withInput();
        }
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOtp()
    {
        if (!session()->has('partner_registration')) {
            return redirect()->route('diantaranexus.register')->withErrors(['error' => 'Sesi registrasi tidak ditemukan. Silakan daftar ulang.']);
        }

        return view('partner.auth.verify-otp', [
            'email' => session('partner_registration.email')
        ]);
    }

    /**
     * Verify OTP and complete registration
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $registrationData = session('partner_registration');
        
        if (!$registrationData) {
            return redirect()->route('diantaranexus.register')->withErrors(['error' => 'Sesi registrasi tidak ditemukan. Silakan daftar ulang.']);
        }

        // Check if OTP is expired
        $expiresAt = \Illuminate\Support\Carbon::parse($registrationData['otp_expires_at']);
        if (now()->gt($expiresAt)) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.']);
        }

        // Verify OTP
        $inputOtp = trim((string) $request->otp);
        $sessionOtp = trim((string) ($registrationData['otp'] ?? ''));

        \Log::info('[Partner OTP] Verifying OTP', [
            'email' => $registrationData['email'] ?? null,
            'input' => $inputOtp,
            'expected' => $sessionOtp,
        ]);

        if ($inputOtp !== $sessionOtp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // Create partner account
        $partner = Partner::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'phone' => $registrationData['phone'],
            'organization_name' => $registrationData['organization_name'],
            'address' => $registrationData['address'],
            'status' => 'verified', // Langsung verified setelah OTP
            'email_verified_at' => now(),
        ]);

        // Store partner data for organization setup
        $request->session()->put('new_partner', [
            'id' => $partner->id,
            'name' => $partner->name,
            'email' => $partner->email,
            'organization_name' => $registrationData['organization_name'],
        ]);

        // Clear registration session
        $request->session()->forget('partner_registration');

        return redirect()->route('diantaranexus.setup-organization')->with('success', 
            'Verifikasi berhasil! Sekarang lengkapi data organisasi Anda.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $registrationData = session('partner_registration');
        
        if (!$registrationData) {
            return redirect()->route('diantaranexus.register')->withErrors(['error' => 'Sesi registrasi tidak ditemukan. Silakan daftar ulang.']);
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update session with new OTP
        $registrationData['otp'] = $otp;
        $registrationData['otp_expires_at'] = now()->addMinutes(5);
        $request->session()->put('partner_registration', $registrationData);

        // Send new OTP email
        try {
            Mail::send('emails.partner-otp', ['otp' => $otp, 'name' => $registrationData['name']], function ($message) use ($registrationData) {
                $message->to($registrationData['email'])
                        ->subject('Verifikasi Akun Partner Nexus - Kode OTP Baru');
            });

            \Log::info('[Partner OTP] Resent OTP', [
                'email' => $registrationData['email'],
                'otp' => $otp,
                'expires_at' => $registrationData['otp_expires_at']
            ]);

            return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirim ke email Anda.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email verifikasi. Silakan coba lagi.']);
        }
    }

    /**
     * Show organization setup form
     */
    public function showOrganizationSetup()
    {
        if (!session()->has('new_partner')) {
            return redirect()->route('diantaranexus.login')->withErrors(['error' => 'Sesi tidak ditemukan. Silakan login.']);
        }

        return view('partner.auth.setup-organization', [
            'partner' => session('new_partner')
        ]);
    }

    /**
     * Handle organization setup
     */
    public function setupOrganization(Request $request)
    {
        $partnerData = session('new_partner');
        
        if (!$partnerData) {
            return redirect()->route('diantaranexus.login')->withErrors(['error' => 'Sesi tidak ditemukan. Silakan login.']);
        }

        $validator = Validator::make($request->all(), [
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|string',
            'position' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'agree_terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create organization
        $organization = PartnerOrganization::create([
            'partner_id' => $partnerData['id'],
            'name' => $request->organization_name,
            'type' => $request->organization_type,
            'description' => null,
            'website' => null,
            'contact_info' => [
                'phone' => Partner::find($partnerData['id'])->phone,
                'email' => $partnerData['email'],
                'social_media' => null,
            ],
            'business_info' => [
                'category' => $request->category,
                'position' => $request->position,
            ],
            'status' => 'active',
        ]);

        // Clear session
        $request->session()->forget('new_partner');

        // Auto login the partner
        $partner = Partner::find($partnerData['id']);
        Auth::guard('partner')->login($partner);

        return redirect()->route('diantaranexus.dashboard')->with('success', 
            'Selamat! Organisasi Anda telah berhasil dibuat. Selamat datang di Nexus!');
    }

    /**
     * Handle partner logout
     */
    public function logout(Request $request)
    {
        Auth::guard('partner')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('diantaranexus.login');
    }
}
