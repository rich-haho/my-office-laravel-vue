<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Booking
     */
    public $booking;

    /**
     * Create a new notification instance.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return BookingStatusUpdated
     */
    public function build()
    {
        return $this
            ->subject(__('booking.status_updated.subject'))
            ->markdown('emails.booking.status')
        ;
    }
}
