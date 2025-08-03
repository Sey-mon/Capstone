<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestBrevoEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:brevo-email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Brevo API connection and send a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'shim40907@gmail.com';
        
        $this->info('Testing Brevo API connection...');
        $this->info('Email will be sent to: ' . $email);
        
        try {
            // Test the connection by sending a simple email
            Mail::raw('This is a test email from your BMI Malnutrition Monitoring System to verify that Brevo API connection is working properly.', function (Message $message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Brevo API Connection')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info('✅ Test email sent successfully!');
            $this->info('Please check your inbox at: ' . $email);
            
            // Display current mail configuration
            $this->info('');
            $this->info('Current mail configuration:');
            $this->info('Mail Driver: ' . config('mail.default'));
            $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
            $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
            $this->info('SMTP Username: ' . config('mail.mailers.smtp.username'));
            $this->info('From Address: ' . config('mail.from.address'));
            $this->info('From Name: ' . config('mail.from.name'));
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email');
            $this->error('Error: ' . $e->getMessage());
            
            // Display debugging information
            $this->info('');
            $this->info('Debug information:');
            $this->info('Mail Driver: ' . config('mail.default'));
            $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
            $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
            $this->info('SMTP Encryption: ' . config('mail.mailers.smtp.encryption'));
            $this->info('SMTP Username: ' . config('mail.mailers.smtp.username'));
            
            return 1;
        }
        
        return 0;
    }
}
