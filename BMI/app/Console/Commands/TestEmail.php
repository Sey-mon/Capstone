<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email?}';

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
        $email = $this->argument('email') ?? 'shim40907@gmail.com';
        
        $this->info("Testing email configuration...");
        $this->info("Sending test email to: {$email}");
        
        try {
            Mail::raw('This is a test email from LakasApp to verify your Brevo email configuration is working correctly!', function (Message $message) use ($email) {
                $message->to($email)
                        ->subject('LakasApp - Email Configuration Test')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info('✅ Email sent successfully!');
            $this->info('Please check your inbox (and spam folder) for the test email.');
            
        } catch (\Exception $e) {
            $this->error('❌ Email sending failed!');
            $this->error('Error: ' . $e->getMessage());
            $this->error('Please check your Brevo configuration in .env file.');
            
            // Show current mail configuration
            $this->info('Current mail configuration:');
            $this->info('MAIL_MAILER: ' . config('mail.default'));
            $this->info('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
            $this->info('MAIL_PORT: ' . config('mail.mailers.smtp.port'));
            $this->info('MAIL_USERNAME: ' . config('mail.mailers.smtp.username'));
            $this->info('MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
            $this->info('MAIL_FROM_NAME: ' . config('mail.from.name'));
        }
    }
}
