<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\ResendMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PartnerPasswordResetLinkController extends Controller
{
    public function create(Request $request): View
    {
        return view('partner.auth.forgot-password', [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $partner = Partner::where('email', $request->email)->first();

            if (!$partner) {
                return back()->with('status', 'Link reset password telah dikirim ke email Anda jika akun tersebut terdaftar.');
            }

            $token = Password::broker('partners')->createToken($partner);

            $resetUrl = url(route('diantaranexus.password.reset', [
                'token' => $token,
                'email' => $partner->email,
            ], false));

            $sent = app(ResendMailer::class)->sendPasswordReset($partner->email, $resetUrl, $partner->name);

            if ($sent) {
                return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
            }

            return back()->with('error', 'Gagal mengirim link reset password. Silakan coba lagi atau hubungi administrator.');

        } catch (\Exception $e) {
            \Log::error('Partner password reset error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengirim email reset password. Silakan coba lagi atau hubungi administrator.');
        }
    }
}
