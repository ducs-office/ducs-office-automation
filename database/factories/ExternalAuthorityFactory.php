<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ExternalAuthority;
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

$factory->state(ExternalAuthority::class, 'cosupervisor', [
    'is_cosupervisor' => true,
]);
