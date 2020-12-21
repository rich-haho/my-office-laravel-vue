<?php

namespace App\Jobs;

use App\Models\Rating;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Booking;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingDelivered;

class BookingAfterTwoDays implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $day2ago = date('Y-m-d H:i', strtotime('-2days'));
        $day3ago = date('Y-m-d H:i', strtotime('-3days'));

        $bookings = Booking::where('status', 'delivered')
            ->whereBetween('updated_at', [$day3ago, $day2ago])
            ->get();

        foreach ($bookings as $booking) {
            $productId = $booking->product ? $booking->product->product_id : null;
            $hasRatings = Rating::where('product_id', '=', $productId)
                ->where('user_id', $booking->user->id)
                ->count();

            if ($hasRatings === 0) {
                Notification::send($booking->user, new BookingDelivered($booking));
            }
        }
    }
}
