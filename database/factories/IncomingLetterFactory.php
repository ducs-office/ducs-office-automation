<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\IncomingLetter;
use Faker\Generator as Faker;

$factory->define(IncomingLetter::class, function (Faker $faker) {
    return [
        'date' => $faker->date('Y-m-d'),
        'serial_no' => $faker->regexify('CS/\D/\2019/\[0-9]{4}'),
        'received_id' => $faker->regexify('Dept/\RD/\[0-9]{4}'),
        'sender' => $faker->name,
        'recipient_id' => function() {
            return factory(User::class)->create()->id;
        },
        'handover_id' => function() {
            return factory(User::class)->create()->id;
        },
        'priority' => $faker->randomElement([1,2,3]),
        'subject' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});
