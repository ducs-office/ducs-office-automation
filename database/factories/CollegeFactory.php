<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\College;
use Faker\Generator as Faker;

$factory->define(College::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('DU-[A-Z]{3,5}-[0-9]{2}'),
        'name' => $faker->unique()->words(4,true)
    ];
});
