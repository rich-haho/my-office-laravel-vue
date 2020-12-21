<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use App\Models\Service;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Service::class, function (Faker $faker, array $data = []) {
    return [
        'name'      => $faker->city,
    ];
});
