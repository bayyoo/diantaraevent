<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email : Email address to send test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing Brevo email configuration via HTTP API...');
        
        try {
            $html = '<p>This is a test email from Diantara system sent via Brevo HTTP API. If you receive this, your email configuration is working correctly!</p>';

            $sent = app(\App\Services\BrevoEmailService::class)->sendEmail(
                $email,
                $email,
                'Test Email - Diantara System',
                $html
            );

            if ($sent) {
                $this->info("✅ Test email sent successfully to: {$email}");
                $this->info('Please check the inbox (and spam folder) of the recipient.');
            } else {
                $this->error('❌ Failed to send test email via Brevo. Check Brevo API key and sender configuration.');
            }

        } catch (\Exception $e) {
            $this->error("❌ Failed to send test email: " . $e->getMessage());
            $this->error('Error details: ' . $e->getTraceAsString());
        }
    }
}
