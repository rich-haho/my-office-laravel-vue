<?php

namespace App\Models;

use App\Events\PartnerSaving;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

/**
 * @method static where(string $string , string $string1 , $row)
 */
class Partner extends Model
{
    use Notifiable;
    use HasTranslations;

    public $translatable = ['public_description'];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'contact_name',
        'public_description',
        'private_description',
        'commission_percentage',
        'connected_stripe_id',
        'enable_stripe_connect'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'commission_percentage' => 'float',
    ];

    /**
     * Dispatches custom event on Laravel Eloquent native events.
     *
     * @var string[]
     */
    protected $dispatchesEvents = [
        'saving' => PartnerSaving::class,
    ];

    /**
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * @return HasOne
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    /**
     * @return BelongsToMany
     */
    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }
}
