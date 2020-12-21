<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * @var string
     */
    protected $table = 'booking_products';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'product_id',
        'description',
        'date_slot',
        'start_time',
        'end_time',
        'price',
        'currency',
        'image'
    ];

    /*
     * @return HasMany
     */
    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
