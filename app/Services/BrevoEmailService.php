<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoEmailService
{
    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent, array $params = []): bool
    {
        $apiKey = config('services.brevo.api_key', env('BREVO_API_KEY'));

        if (empty($apiKey)) {
            Log::error('Brevo API key is not set.');
            return false;
        }

        $payload = [
            'sender' => [
                'email' => config('services.brevo.sender_email', env('BREVO_SENDER_EMAIL')),
                'name'  => config('services.brevo.sender_name', env('BREVO_SENDER_NAME', config('app.name'))),
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name'  => $toName,
                ],
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ];

        if (!empty($params)) {
            $payload['params'] = $params;
        }

        try {
            $response = Http::withHeaders([
                'api-key'      => $apiKey,
                'accept'       => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $payload);

            if (!$response->successful()) {
                Log::error('Brevo API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Brevo API exception', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
