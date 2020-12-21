<?php

namespace App\Listeners;

use App\Events\BookingSaving;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusUpdated;

class ModifyBookingStatus
{
    /**
     * Handle the event.
     *
     * @param  BookingSaving  $event
     * @return void
     */
    public function handle(BookingSaving $event)
    {
        $booking = $event->booking;
        $originalBooking = $booking->getOriginal();

        if ($originalBooking['status'] !== $booking->status) {
            Mail::to($booking->user)
                ->locale($booking->user->state->locale->name ?? 'fr')
                ->send(new BookingStatusUpdated($booking))
            ;
        }
    }
}
