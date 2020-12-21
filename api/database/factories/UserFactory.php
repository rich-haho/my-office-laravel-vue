<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker, array $data = []) {
    return [
        'client_id'             => Arr::get($data, 'client_id', rand(1, 3)),
        'name'                  => $faker->name,
        'email'                 => Arr::get($data, 'email', $faker->unique()->safeEmail),
        'email_verified_at'     => now(),
        'password'              => Hash::make('secret'),
    ];
});
