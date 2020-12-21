<?php

namespace App\Providers;

use App\Events\AdminSaving;
use App\Events\BookingSaving;
use App\Events\PartnerSaving;
use App\Events\ProductLogging;
use App\Events\SearchLogSaving;
use App\Events\ServiceLogging;
use App\Listeners\LimitLogSearch;
use App\Listeners\LogSearch;
use App\Listeners\ModifyBookingStatus;
use App\Listeners\SyncAdminPartner;
use App\Listeners\SyncPartnerAdmin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BookingSaving::class => [
            ModifyBookingStatus::class,
        ],
        ProductLogging::class => [
            LogSearch::class,
        ],
        ServiceLogging::class => [
            LogSearch::class,
        ],
        SearchLogSaving::class => [
            LimitLogSearch::class
        ],
        PartnerSaving::class => [
            SyncPartnerAdmin::class
        ],
        AdminSaving::class => [
            SyncAdminPartner::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
