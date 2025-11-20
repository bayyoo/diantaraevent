<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResendMailer
{
    /**
     * Send OTP email using Resend HTTP API.
     */
    public function sendOtp(string $toEmail, string $otp, ?string $toName = null): bool
    {
        $subject = 'Kode OTP Verifikasi Akun Diantara';
        $recipientName = $toName ?: $toEmail;

        $html = view('emails.verify-otp-simple', [
            'otp' => $otp,
            'email' => $toEmail,
        ])->render();

        try {
            $brevo = app(\App\Services\BrevoEmailService::class);
            return $brevo->sendEmail($toEmail, $recipientName, $subject, $html);
        } catch (\Throwable $e) {
            Log::error('ResendMailer: exception when sending OTP email', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send password reset link email using Resend HTTP API.
     */
    public function sendPasswordReset(string $toEmail, string $resetUrl, ?string $toName = null): bool
    {
        $subject = 'Reset Password Akun Diantara';
        $recipientName = $toName ?: $toEmail;

        $html = view('emails.password-reset-simple', [
            'resetUrl' => $resetUrl,
            'email' => $toEmail,
        ])->render();

        try {
            $brevo = app(\App\Services\BrevoEmailService::class);
            return $brevo->sendEmail($toEmail, $recipientName, $subject, $html);
        } catch (\Throwable $e) {
            Log::error('ResendMailer: exception when sending password reset email', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
