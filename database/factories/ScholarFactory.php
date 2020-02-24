<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Scholar;
use Faker\Generator as Faker;

$factory->define(Scholar::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => $faker->email(),
        'password' => '$2y$10$BrUcxS6jKnitbT4tRCog2eR00DCkJT.VXOhxRAv2Xxoq.77ow2fV2', // password
    ];
});
