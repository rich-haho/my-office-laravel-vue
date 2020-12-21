<?php

namespace App\Models;

use App\Events\AdminSaving;
use App\Notifications\AdminResetPassword;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Admin
 * @package App\Models
 */
class Admin extends Authenticatable implements HasLocalePreference
{
    use Notifiable;
    use HasRoles;
    /**
     * @var string
     */
    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * @var array
     */
    protected $attributes = [
        'admin'     => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'admin'             => 'boolean',
    ];

    /**
     * Dispatches custom event on Laravel Eloquent native events.
     *
     * @var string[]
     */
    protected $dispatchesEvents = [
        'saving' => AdminSaving::class,
    ];

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }

    /**
     * Get the preferred locale of the entity.
     *
     * @return Repository|Application|mixed|string|null
     */
    public function preferredLocale()
    {
        return config('app.locale', 'fr');
    }

    /**
     * Check if user is a super admin
     * @return bool
     */
    public function isSuperAdmin() : bool
    {
        return $this->admin === true;
    }

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return config('app.locale', 'fr');
    }

    /**
     * @return HasOne
     */
    public function partner()
    {
        return $this->hasOne(Partner::class);
    }
}
