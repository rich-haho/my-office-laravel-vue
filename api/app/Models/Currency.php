<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'code'
    ];
}
