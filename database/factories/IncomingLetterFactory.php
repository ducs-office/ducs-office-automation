<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\IncomingLetter;
use App\User;
use Faker\Generator as Faker;

$factory->define(IncomingLetter::class, static function (Faker $faker) {
    return [
        'date' => $faker->date('Y-m-d'),
        'received_id' => $faker->regexify('Dept/\RD/\[0-9]{4}'),
        'sender' => $faker->name,
        'recipient_id' => static function () {
            return factory(User::class)->create()->id;
        },
        'creator_id' => static function () {
            return factory(User::class)->create()->id;
        },
        'priority' => $faker->randomElement([1, 2, 3]),
        'subject' => $faker->sentence,
        'description' => $faker->paragraph,
    ];
});
