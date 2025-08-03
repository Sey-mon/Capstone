<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Swift_TransportException;

class TestBrevoConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:brevo-connection {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Brevo SMTP connection with detailed diagnostics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'shim40907@gmail.com';
        
        $this->info('🔍 Brevo SMTP Connection Diagnostics');
        $this->info('=====================================');
        
        // Display current configuration
        $this->displayConfiguration();
        
        // Test SMTP connection
        $this->testSmtpConnection();
        
        // Send test email
        $this->sendTestEmail($email);
        
        return 0;
    }
    
    private function displayConfiguration()
    {
        $this->info('');
        $this->info('📋 Current Mail Configuration:');
        $this->line('  Driver: ' . config('mail.default'));
        $this->line('  Host: ' . config('mail.mailers.smtp.host'));
        $this->line('  Port: ' . config('mail.mailers.smtp.port'));
        $this->line('  Encryption: ' . config('mail.mailers.smtp.encryption'));
        $this->line('  Username: ' . config('mail.mailers.smtp.username'));
        $this->line('  From Address: ' . config('mail.from.address'));
        $this->line('  From Name: ' . config('mail.from.name'));
    }
    
    private function testSmtpConnection()
    {
        $this->info('');
        $this->info('🔌 Testing SMTP Connection...');
        
        try {
            $transport = app('mail.manager')->getSwiftMailer()->getTransport();
            $transport->start();
            $this->info('✅ SMTP connection successful!');
            $transport->stop();
        } catch (\Exception $e) {
            $this->error('❌ SMTP connection failed: ' . $e->getMessage());
            $this->info('');
            $this->info('💡 Troubleshooting tips:');
            $this->line('  1. Check if your Brevo API key is correct');
            $this->line('  2. Verify your Brevo account is active');
            $this->line('  3. Check if your server can reach smtp-relay.brevo.com:587');
            $this->line('  4. Ensure your Brevo account has sending credits');
        }
    }
    
    private function sendTestEmail($email)
    {
        $this->info('');
        $this->info('📧 Sending test email to: ' . $email);
        
        try {
            Mail::raw('This is a detailed test email from your BMI Malnutrition Monitoring System.

Configuration Test Results:
- Mail Driver: ' . config('mail.default') . '
- SMTP Host: ' . config('mail.mailers.smtp.host') . '
- SMTP Port: ' . config('mail.mailers.smtp.port') . '
- From Address: ' . config('mail.from.address') . '
- Timestamp: ' . now()->format('Y-m-d H:i:s T') . '

If you receive this email, your Brevo configuration is working correctly!

Best regards,
BMI Malnutrition Monitoring System', function (Message $message) use ($email) {
                $message->to($email)
                        ->subject('✅ Brevo SMTP Test - ' . now()->format('H:i:s'))
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info('✅ Test email sent successfully!');
            $this->info('📬 Please check your inbox at: ' . $email);
            $this->info('');
            $this->info('🕐 Email should arrive within 1-2 minutes');
            $this->info('📁 Also check your spam/junk folder');
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email');
            $this->error('Error details: ' . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'Connection could not be established')) {
                $this->info('');
                $this->info('🔧 Network connectivity issue detected');
                $this->line('  - Check if port 587 is blocked by firewall');
                $this->line('  - Try using port 25 or 2525 instead');
                $this->line('  - Contact your hosting provider about SMTP restrictions');
            }
            
            if (str_contains($e->getMessage(), 'authentication')) {
                $this->info('');
                $this->info('🔑 Authentication issue detected');
                $this->line('  - Verify your Brevo SMTP username and password');
                $this->line('  - Check if your Brevo account is suspended');
                $this->line('  - Generate new SMTP credentials in Brevo dashboard');
            }
        }
    }
}
