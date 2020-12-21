<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Asset;
use Faker\Generator as Faker;

$factory->define(Asset::class, function (Faker $faker) {
    return [
        'path'      => 'http://via.placeholder.com/300',
        'disk'      => Asset::EXTERNAL_DISK,
        'filename'  => $faker->word,
    ];
});
