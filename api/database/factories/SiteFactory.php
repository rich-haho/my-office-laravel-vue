<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use App\Models\Site;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Site::class, function (Faker $faker, array $data = []) {
    return [
        'name'            => $faker->city,
        'open_time'       => $faker->sentence(),
        'phone_number'    => $faker->phoneNumber,
        'client_id'       => Arr::get($data, 'client_id', Client::all()->random()->id),
        'address'         => $faker->address,
    ];
});
