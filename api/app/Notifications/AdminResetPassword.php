<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as NotificationResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPassword extends NotificationResetPassword
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Admin Reset Password'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->action(
                __('Reset Password'),
                url(
                    env('PLATFORM_URL').'/zen-admin/reset/password/' . $this->token
                    . '/' . $notifiable->getEmailForPasswordReset()
                )
            )
            ->line(__('If you did not request a password reset, no further action is required.'))
            ->line(__('Thank you for using Zen Office, and see you soon!'));
    }
}
