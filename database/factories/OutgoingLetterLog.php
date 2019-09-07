<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OutgoingLetterLog;
use Faker\Generator as Faker;

$factory->define(OutgoingLetterLog::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTime(),
        'type' => $faker->realText(50),
        'recipient' => $faker->name,
        'sender_id' => rand(1, App\User::count()),
        'description' => $faker->realText(400),
        'amount' => $faker->randomFloat(2,1),
    ];
});
