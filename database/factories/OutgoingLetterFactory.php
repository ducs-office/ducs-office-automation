<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\OutgoingLetter;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(OutgoingLetter::class, static function (Faker $faker) {
    return [
        'creator_id' => static function () {
            return factory(User::class)->create()->id;
        },
        'date' => $faker->date('Y-m-d'),
        'type' => $type = $faker->randomElement(['Bill', 'Notesheet', 'General']),
        'subject' => $faker->sentence,
        'recipient' => $faker->name,
        'sender_id' => static function () {
            return factory(User::class)->create()->id;
        },
        'description' => $faker->paragraph,
        'amount' => $type === 'Bill' ? $faker->randomFloat(2, 10.00, 20000.00) : null,
    ];
});
