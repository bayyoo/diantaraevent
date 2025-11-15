<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

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
        
        $this->info('Testing email configuration...');
        $this->info('Email driver: ' . config('mail.default'));
        $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
        $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
        $this->info('From Address: ' . config('mail.from.address'));
        
        try {
            Mail::raw('This is a test email from Diantara system. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Diantara System')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info("✅ Test email sent successfully to: {$email}");
            $this->info('Please check the inbox (and spam folder) of the recipient.');
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send test email: " . $e->getMessage());
            $this->error('Error details: ' . $e->getTraceAsString());
            
            // Check common issues
            $this->warn('Common issues to check:');
            $this->warn('1. MAIL_* environment variables in .env file');
            $this->warn('2. SMTP credentials (username/password)');
            $this->warn('3. SMTP host and port settings');
            $this->warn('4. Firewall blocking SMTP ports');
            $this->warn('5. Gmail: Enable "Less secure app access" or use App Password');
        }
    }
}
