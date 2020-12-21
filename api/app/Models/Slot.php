<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Tag
 * @package App\Models
 */
class Slot extends Model
{
    /**
     * @var string
     */
    protected $table = 'slots';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'days',
        'start_date',
        'end_date',
        'min_participant',
        'max_participant',
    ];

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    /**
     * @return HasMany
     */
    public function slotTimes()
    {
        return $this->hasMany(SlotTime::class);
    }

    /**
     * @return HasMany
     */
    public function slotBookings()
    {
        return $this->hasMany(SlotBooking::class, 'slot_id', 'id');
    }
}
