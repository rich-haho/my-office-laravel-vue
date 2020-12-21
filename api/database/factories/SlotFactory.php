<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\Slot;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Slot::class, function (Faker $faker, array $data = []) {
    return [
        'product_id'      => Arr::get($data, 'product_id', Product::all()->random()->id),
        'days'            => $faker->regexify('[0-1]{7}'),
        'start_date'      => $faker->dateTimeBetween('now', '+20 days'),
        'end_date'        => $faker->dateTimeBetween('now + 30 days', '+60 days'),
        'min_participant' => $faker->numberBetween(0,5),
        'max_participant' => $faker->numberBetween(6,12),
    ];
});
