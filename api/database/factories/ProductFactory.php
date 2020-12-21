<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;


$factory->define(Product::class, function (Faker $faker, array $data = []) {
    return [
        'name'                  => $faker->word,
        'description'           => $faker->sentence,
        'price'                 => $faker->randomNumber (4),
        'price_reduction'       => $faker->randomNumber (2),
        'manual_product'        => $faker->boolean,
        'moment_product'        => $faker->boolean,
        'partner_id'            => Arr::get($data, 'partner_id', Partner::all()->random()->id),
        'service_id'            => Arr::get($data, 'service_id', Service::all()->random()->id)
    ];
});
