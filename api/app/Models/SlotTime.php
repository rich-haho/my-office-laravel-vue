<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Tag
 * @package App\Models
 */
class SlotTime extends Model
{
    /**
     * @var string
     */
    protected $table = 'slot_times';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slot_id',
        'start_time',
        'end_time',
    ];

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }
}
