<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @method static where(string $string , string $string1 , $row)
 */
class Service extends Model
{
    use HasTranslations;

    /**
     * @var string
     */
    protected $table = 'services';

    /**
     * @var string[]
     */
    public $translatable = ['name', 'description'];

    /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $fillable = [
        'name',
        'external_link'
    ];

    /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
    protected $dates = [
        'created_at', 'deleted_at',
    ];
    /**
     * Get all image_url of service in Service Model
     *
     * @return BelongsToMany
     */
    public function assets()
    {
        return $this->belongsToMany('App\Models\Asset', 'service_assets', 'service_id');
    }

    /*
     * @return hasMany
     */
    public function favorites()
    {
        return $this->belongsToMany('App\Models\User', 'user_services_favorites');
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

    /**
     * @param mixed $query
     * @param int $userId
     * @return mixed
     */
    public function scopeUserFavorite($query, int $userId)
    {
        return $query->join('user_services_favorites', function ($join) use ($userId) {
            $join
                ->on('user_services_favorites.service_id', '=', 'services.id')
                ->on('user_services_favorites.user_id', '=', DB::raw($userId));
        });
    }

    /**
     * @return BelongsToMany
     */
    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all searches associated to this service.
     * @return MorphMany
     */
    public function searches()
    {
        return $this->morphMany(SearchLog::class, 'loggable');
    }
}
