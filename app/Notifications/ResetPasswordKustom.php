<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordKustom extends Notification
{
    use Queueable;

    /**
     * Token reset password.
     *
     * @var string
     */
    public $token;

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
     * Bangun representasi email dari notifikasi.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Url untuk tombol reset
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Body Email
        return (new MailMessage)
            ->subject(Lang::get('Reset Password'))
            ->greeting(Lang::get('Hi!'))
            ->line(Lang::get('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.'))
            ->line(Lang::get('Silakan klik tombol di bawah untuk mereset password Anda:'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('Link reset password ini akan kedaluwarsa dalam 60 menit.'))
            ->line(Lang::get('Jika Anda tidak meminta reset password, abaikan email ini.'))
            ->salutation(Lang::get('Hormat kami,'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
