<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Programme;
use Faker\Generator as Faker;

$factory->define(Programme::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('[A-Z0-9]{3}[0-9\-][0-9]{6}'),
        'wef' => $faker->date('Y-m-d'),
        'name' => $faker->words(3, true),
        'type' => $faker->randomElement(['Under Graduate(U.G.)', 'Post Graduate(P.G.)']),
    ];
});
