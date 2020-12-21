<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'client_id',
        'open_time',
        'phone_number',
        'address',
        'fm_services',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'fm_services' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    /**
     * @return HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * @param int $clientId
     * @return mixed
     */
    public function scopeClientSites($query, $clientId)
    {
        return $query->where('client_id', $clientId)->select('id', 'name');
    }

    /**
     * @return BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
