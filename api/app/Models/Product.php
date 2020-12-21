<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class Product
 * @package App\Models
 * @method static where(string $string , string $string1 , $row)
 */
class Product extends Model
{
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var string[]
     */
    public $translatable = ['name','description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'price_reduction',
        'partner_id',
        'commission_percentage',
        'enable_custom_commission',
        'manual_product',
        'moment_product',
        'service_id',
        'currency_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'manual_product'           => 'boolean',
        'enable_custom_commission' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }

    /**
     * @return belongsToMany
     */
    public function assets()
    {
        return $this->belongsToMany('App\Models\Asset', 'product_assets', 'product_id');
    }

    /**
     * @return belongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    /*
     * @return BelongsTo
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }

    /**
     * @return belongsToMany
     */
    public function sites()
    {
        return $this->belongsToMany('App\Models\Site')->withTimestamps();
    }

    /*
     * @return hasMany
     */
    public function favorites()
    {
        return $this->belongsToMany('App\Models\User', 'user_products_favorites');
    }


    /**
     * @return HasMany
     */
    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    /*
     * @param  int $user_id
     * @return boolean
     */
    public function isFavorite($user_id = null)
    {
        return $this->favorites()
        ->where('user_id', ($user_id) ? $user_id : Auth::id())
                                ->exists();
    }

    /*
     * @return BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    /**
     * Getting all associated search logs with this model.
     *
     * @return MorphMany
     */
    public function searches()
    {
        return $this->morphMany(SearchLog::class, 'loggable');
    }

    /**
     * @param mixed $query
     * @param int $userId
     * @return mixed
     */
    public function scopeUserFavorite($query, int $userId)
    {
        return $query->join('user_products_favorites', function ($join) use ($userId) {
            $join
                ->on('user_products_favorites.product_id', '=', 'products.id')
                ->on('user_products_favorites.user_id', '=', DB::raw($userId));
        });
    }

    /**
     * @return HasMany
     */
    public function bookingProducts()
    {
        return $this->hasMany('App\Models\Booking\Product');
    }

    /**
     * @return HasMany
     */
    public function ratings()
    {
        return $this->hasMany('App\Models\Rating')->orderBy('created_at', 'desc');
    }
}
