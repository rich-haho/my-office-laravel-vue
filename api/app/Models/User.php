<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Models\SearchLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as InterfaceCanResetPassword;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements InterfaceCanResetPassword, MustVerifyEmail, HasLocalePreference
{
    use Notifiable;
    use CanResetPassword;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'client_id', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'superuser'         => 'boolean'
    ];

    /**
     * Check if user is a super admin
     * @return bool
     */
    public function isSuperAdmin() : bool
    {
        return false;
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     *
     * @return HasOne
     */
    public function state()
    {
        return $this->hasOne('App\Models\State');
    }

    /**
     * Get the preferred locale of the entity.
     *
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->state && $this->state->locale ?
            $this->state->locale->name :
            config('app.locale', 'fr');
    }

    /**
     * @return string|null
     */
    public function preferredLocale()
    {
        return $this->getLocaleCode();
    }

    /**
     *
     * @return BelongsToMany
     */
    public function productFavorites()
    {
        return $this->belongsToMany('App\Models\Product', 'user_products_favorites')->withTimestamps();
    }

    /**
     *
     * @return BelongsToMany
     */
    public function serviceFavorites()
    {
        return $this->belongsToMany('App\Models\Service', 'user_services_favorites')->withTimestamps();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get search logs associated with this model.
     *
     * @return HasMany
     */
    public function searches()
    {
        return $this->hasMany(SearchLog::class);
    }
}
