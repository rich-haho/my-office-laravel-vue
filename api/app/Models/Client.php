<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string , string $string1 , $row)
 */
class Client extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * @return HasMany
     */
    public function domains()
    {
        return $this->hasMany('App\Models\EmailDomain');
    }

    /**
     * @return HasMany
     */
    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
