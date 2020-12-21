<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Slot;
use App\Models\SlotTime;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(SlotTime::class, function (Faker $faker, array $data = []) {
    return [
        'slot_id'    => Arr::get($data, 'slot_id', Slot::all()->random()->id),
        'start_time' => $faker->numberBetween($min = 8, $max = 12).':00:00',
        'end_time'   => $faker->numberBetween($min = 13, $max = 22).':00:00'
    ];
});
