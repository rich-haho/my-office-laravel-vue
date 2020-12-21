<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Locale
 * @package App\Models
 */
class Locale extends Model
{
     /**
     * @var string
     */
    protected $table = 'locales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
