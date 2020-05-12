<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ExternalAuthority;
use Faker\Generator as Faker;

$factory->define(ExternalAuthority::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'designation' => $faker->word,
        'affiliation' => $faker->word,
        'phone' => $faker->phoneNumber,
    ];
});
