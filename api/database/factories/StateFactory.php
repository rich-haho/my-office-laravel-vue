<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\State;
use App\Models\User;
use App\Models\Site;
use App\Models\Locale;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(State::class, function (Faker $faker, array $data = []) {
    return [
        'user_id'      => Arr::get($data, 'user_id', User::all()->random()->id),
        'site_id'      => Arr::get($data, 'site_id', Site::all()->random()->id),
        'locale_id'    => Arr::get($data, 'locale_id', Locale::all()->random()->id)
    ];
});
