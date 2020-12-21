<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class State
 * @package App\Models
 */
class State extends Model
{

     /**
     * @var string
     */
    protected $table = 'user_state';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'site_id',
        'locale_id'
    ];

    /**
     * @return BelongsTo
     */
    public function locale()
    {
        return $this->belongsTo('App\Models\Locale');
    }

     /**
     * @return BelongsTo
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Site', 'site_id');
    }
}
