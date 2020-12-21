<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enum\BookingStatus;
use App\Events\BookingSaving;

class Booking extends Model
{
    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updating' => BookingSaving::class,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'partner_id',
        'site_id',
        'date',
        'quantity',
        'status',
        'manual_product',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Booking\Product');
    }

    /**
     * @return BelongsTo
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @param BookingStatus $status
     */
    public function setStatus(BookingStatus $status) : void
    {
        $this->status = $status;
    }
}
