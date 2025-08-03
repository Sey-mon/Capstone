<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NutritionistApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
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
        return (new MailMessage)
            ->subject('Account Approved - BMI Monitoring System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your nutritionist account has been approved by the administrator.')
            ->line('You can now access all nutritionist features in the BMI Monitoring System.')
            ->action('Login to Dashboard', route('login'))
            ->line('Thank you for joining our team!')
            ->salutation('Best regards, BMI Monitoring System Team');
    }
}
