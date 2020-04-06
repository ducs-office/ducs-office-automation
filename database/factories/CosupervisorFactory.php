<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Cosupervisor;
use Faker\Generator as Faker;

$factory->define(Cosupervisor::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'designation' => $faker->sentence,
        'affiliation' => $faker->sentence,
    ];
});
