<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Booking\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker, array $data = []) {
    return [
        'name'                  => $faker->word,
        'description'           => $faker->sentence,
        'price'                 => $faker->randomNumber(4),
        'currency'              => $faker->randomElement(['CHF', 'EUR']),
        'image'                 => $faker->imageUrl(),
    ];
});
