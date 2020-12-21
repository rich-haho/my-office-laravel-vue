<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingDelivered extends Notification implements ShouldQueue
{
    public $booking;
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Rate your last order on Zen-Office'))
            ->greeting(__('Dear :name,', ['name' => $this->booking->user->name]))
            ->line(
                __('mail.rating.rate')
            )
            ->action(
                __('View Product'),
                env('PLATFORM_URL').'/products/'.$this->booking->product->product->id
            )
            ->line(__('Thank you for using Zen Office, and see you soon!'))
            ->salutation('Anna');
    }
}
