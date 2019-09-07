<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OutgoingLetterLog;
use App\User;
use Faker\Generator as Faker;

$factory->define(OutgoingLetterLog::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTime(),
        'type' => $faker->realText(50),
        'recipient' => $faker->name,
        'sender_id' => function() {
            return factory(User::class)->create()->id;
        },
        'description' => $faker->realText(400),
        'amount' => $faker->randomFloat(2, 10.00, 20000.00),
    ];
});
