<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Create the verification URL with proper signing
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'token' => $this->token,
            ]
        );

        try {
            // Try to get the email template using direct DB query as fallback
            $template = null;
            
            // First try with the EmailTemplate model
            if (class_exists(EmailTemplate::class)) {
                $template = EmailTemplate::where('name', 'email_verification')
                    ->where('is_active', true)
                    ->first();
            }
            
            // Fallback to direct database query if model fails
            if (!$template) {
                $templateData = DB::table('email_templates')
                    ->where('name', 'email_verification')
                    ->where('is_active', true)
                    ->first();
                    
                if ($templateData) {
                    $styling = json_decode($templateData->styling, true) ?? [];
                    
                    $variables = [
                        'app_name' => config('app.name', 'BMI Monitoring System'),
                        'user_name' => $notifiable->name ?? 'User',
                        'verification_url' => $verificationUrl,
                        'font_family' => $styling['font_family'] ?? 'Arial, sans-serif',
                        'container_width' => $styling['container_width'] ?? '600px',
                        'primary_color' => $styling['primary_color'] ?? '#10b981',
                        'secondary_color' => $styling['secondary_color'] ?? '#064e3b',
                        'background_color' => $styling['background_color'] ?? '#f9fafb',
                        'text_color' => $styling['text_color'] ?? '#374151',
                        'button_color' => $styling['button_color'] ?? '#10b981',
                        'button_text_color' => $styling['button_text_color'] ?? '#ffffff',
                    ];

                    // Manual placeholder replacement
                    $renderedSubject = $templateData->subject;
                    $renderedContent = $templateData->content;
                    
                    foreach ($variables as $key => $value) {
                        $renderedSubject = str_replace("{{$key}}", $value, $renderedSubject);
                        $renderedContent = str_replace("{{$key}}", $value, $renderedContent);
                    }

                    return (new MailMessage)
                        ->subject($renderedSubject)
                        ->view('emails.custom-template', [
                            'content' => $renderedContent,
                        ]);
                }
            } else if ($template) {
                // Use the EmailTemplate model if it worked
                $styling = $template->styling ?? [];
                
                $templateData = [
                    'app_name' => config('app.name', 'BMI Monitoring System'),
                    'user_name' => $notifiable->name ?? 'User',
                    'verification_url' => $verificationUrl,
                    'font_family' => $styling['font_family'] ?? 'Arial, sans-serif',
                    'container_width' => $styling['container_width'] ?? '600px',
                    'primary_color' => $styling['primary_color'] ?? '#10b981',
                    'secondary_color' => $styling['secondary_color'] ?? '#064e3b',
                    'background_color' => $styling['background_color'] ?? '#f9fafb',
                    'text_color' => $styling['text_color'] ?? '#374151',
                    'button_color' => $styling['button_color'] ?? '#10b981',
                    'button_text_color' => $styling['button_text_color'] ?? '#ffffff',
                ];

                $renderedSubject = $template->renderSubject($templateData);
                $renderedContent = $template->renderContent($templateData);

                return (new MailMessage)
                    ->subject($renderedSubject)
                    ->view('emails.custom-template', [
                        'content' => $renderedContent,
                    ]);
            }
        } catch (\Exception $e) {
            // Log the error but continue with fallback
            Log::warning('Failed to load email template: ' . $e->getMessage());
        }

        // Fallback to default template if EmailTemplate is not available
        return (new MailMessage)
            ->subject('Verify Your Email Address - BMI Monitoring System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering with the BMI Monitoring System.')
            ->line('Please copy and paste this link into your browser to verify your email address:')
            ->line($verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Best regards, BMI Monitoring System Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
