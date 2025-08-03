<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestBrevoConnection extends Mailable
{
    use Queueable, SerializesModels;

    public $testData;

    /**
     * Create a new message instance.
     */
    public function __construct($testData = null)
    {
        $this->testData = $testData ?: [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'app_name' => config('app.name'),
            'environment' => config('app.env'),
            'brevo_host' => config('mail.mailers.smtp.host'),
            'from_address' => config('mail.from.address'),
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Brevo API Connection Test - BMI System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.test-brevo',
            text: 'emails.test-brevo-text'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
