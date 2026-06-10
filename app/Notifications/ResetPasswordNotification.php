<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        parent::__construct($token);
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Reset Kata Sandi Akun Anda')
            ->greeting('Assalamualaikum ' . $notifiable->name)
            ->line('Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.')
            ->action('Reset Password', $this->resetUrl($notifiable))
            ->line('Tautan ini akan kedaluwarsa dalam ' . config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') . ' menit.')
            ->line('Jika Anda tidak meminta pengaturan ulang kata sandi, tidak perlu melakukan apa-apa.');
    }
}
