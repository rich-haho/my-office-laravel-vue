<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SlotBooking extends Pivot
{
    /**
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'slot_id',
        'quantity',
        'date',
        'start_time',
        'end_time'
    ];
}
