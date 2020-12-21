<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Partner;
use Faker\Generator as Faker;

$factory->define(Partner::class, function (Faker $faker, array $data = []) {
    return [
        'name'                => $faker->name,
        'address'             => $faker->address,
        'phone'               => $faker->phoneNumber,
        'email'               => $faker->unique()->safeEmail,
        'contact_name'        => $faker->company,
        'public_description'  => $faker->sentence,
        'private_description' => $faker->sentence,
    ];
});
