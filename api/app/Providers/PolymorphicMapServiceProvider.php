<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

/**
 * Class PolymorphicMapServiceProvider
 * @package App\Providers
 */
class PolymorphicMapServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'services' => Service::class,
            'products' => Product::class,
        ]);
    }
}
