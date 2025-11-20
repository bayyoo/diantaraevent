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
        $apiKey = config('services.resend.api_key', env('RESEND_API_KEY'));
        $fromEmail = env('RESEND_FROM_ADDRESS', config('mail.from.address'));
        $fromName = env('RESEND_FROM_NAME', config('mail.from.name'));

        if (!$apiKey || !$fromEmail) {
            Log::warning('ResendMailer: missing API key or from address.');
            return false;
        }

        $subject = 'Kode OTP Verifikasi Akun Diantara';
        $toHeader = $toName ? sprintf('%s <%s>', $toName, $toEmail) : $toEmail;

        $html = view('emails.verify-otp-simple', [
            'otp' => $otp,
            'email' => $toEmail,
        ])->render();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.resend.com/emails', [
                'from' => $fromName ? sprintf('%s <%s>', $fromName, $fromEmail) : $fromEmail,
                'to' => [$toHeader],
                'subject' => $subject,
                'html' => $html,
            ]);

            if (!$response->successful()) {
                Log::error('ResendMailer: failed to send OTP email', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
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
        $apiKey = config('services.resend.api_key', env('RESEND_API_KEY'));
        $fromEmail = env('RESEND_FROM_ADDRESS', config('mail.from.address'));
        $fromName = env('RESEND_FROM_NAME', config('mail.from.name'));

        if (!$apiKey || !$fromEmail) {
            Log::warning('ResendMailer: missing API key or from address for password reset.');
            return false;
        }

        $subject = 'Reset Password Akun Diantara';
        $toHeader = $toName ? sprintf('%s <%s>', $toName, $toEmail) : $toEmail;

        $html = view('emails.password-reset-simple', [
            'resetUrl' => $resetUrl,
            'email' => $toEmail,
        ])->render();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.resend.com/emails', [
                'from' => $fromName ? sprintf('%s <%s>', $fromName, $fromEmail) : $fromEmail,
                'to' => [$toHeader],
                'subject' => $subject,
                'html' => $html,
            ]);

            if (!$response->successful()) {
                Log::error('ResendMailer: failed to send password reset email', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('ResendMailer: exception when sending password reset email', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
