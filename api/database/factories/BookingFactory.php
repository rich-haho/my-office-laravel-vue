<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Booking;
use App\Models\User;
use App\Models\Booking\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use App\Enum\BookingStatus;

$factory->define(Booking::class, function (Faker $faker, array $data = []) {
    return [
        'user_id'    => Arr::get($data, 'user_id', User::all()->random()->id),
        'product_id' => Arr::get($data, 'product_id', Product::all()->random()->id),
        'date'       => $faker->dateTime(),
        'quantity'   => $faker->numberBetween(5, 100),
        'status'     => Arr::random(BookingStatus::getValues()),
    ];
});
