<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Slot;
use App\Models\SlotBooking;
use App\Models\Booking;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(SlotBooking::class, function (Faker $faker, array $data = []) {
    $startTime = $faker->boolean(50) ? $faker->numberBetween($min = 8, $max = 12).':00:00' : null;
    $endTime = ($startTime) ? $faker->numberBetween($min = 13, $max = 22).':00:00' : null;

    return [
        'booking_id' => Arr::get($data, 'id', Booking::all()->random()->id),
        'slot_id'    => Arr::get($data, 'slot_id', Slot::all()->random()->id),
        'quantity'   => $faker->numberBetween($min = 1, $max = 3),
        'date'       => $faker->dateTimeBetween('now', '+30 days'),
        'start_time' => $startTime,
        'end_time'   => $endTime
    ];
});
