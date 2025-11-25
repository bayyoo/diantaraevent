<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PartnerResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset URL.
     */
    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

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
            ->subject('Reset Password Akun Partner Nexus')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Partner Nexus Anda.')
            ->action('Reset Password', $this->url)
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }
}
