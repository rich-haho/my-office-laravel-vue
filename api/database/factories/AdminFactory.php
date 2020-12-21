<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin;
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

$factory->define(Admin::class, function (Faker $faker, array $data = []) {
    return [
        'name'                  => Arr::get($data, 'name', $faker->name),
        'email'                 => Arr::get($data, 'email', $faker->unique()->safeEmail),
        'password'              => Arr::get($data, 'password', Hash::make('secret')),
        'remember_token'        => Str::random(10),
        'admin'                 => Arr::get($data, 'admin', 0),
    ];
});
