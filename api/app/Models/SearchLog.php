<?php

namespace App\Models;

use App\Events\SearchLogSaving;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SearchLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'user_id',
    ];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => SearchLogSaving::class,
    ];

    /**
     * Getting the morphed relationship associated to this model.
     *
     * @return MorphTo
     */
    public function loggable()
    {
        return $this->morphTo();
    }

    /**
     * Getting the user associated to this model.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
