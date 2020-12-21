<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBooking extends Notification implements ShouldQueue
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
        $booking = $this->booking;
        $product = $booking->product;
        $mailMessage = (new MailMessage)
            ->subject(__('New booking on Zen Office'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__(':name made a booking', ['name' => $booking->user->name]))
            ->line(__('Booking date: :time', ['time' => $booking->date]));

        if ($product->start_time && $product->end_time) {
            $startTime = substr($product->start_time, 0, 5);
            $endTime = substr($product->end_time, 0, 5);
            $mailMessage->line(__('Time slots: :start - :end', ['start' => $startTime, 'end' => $endTime]));
        }

        $mailMessage->line(__('Quantity: :quantity', ['quantity' => $booking->quantity]))
        ->line(__('Product: :name', ['name' => $booking->product->name]))
        ->line(__('Site: :name', ['name' => $booking->site->name]))
        ->line(__(
            'Price: :price :currency',
            [
                'price' => $product->price * $booking->quantity,
                'currency' => strtoupper($product->currency)
            ]
        ))
        ->action(
            __('Go to the booking file'),
            env('PLATFORM_URL').'/zen-admin'
        )
        ->line(__('Thank you for using Zen Office, and see you soon!'));

        return $mailMessage;
    }
}
