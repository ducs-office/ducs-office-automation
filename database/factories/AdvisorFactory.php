<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Advisor;
use App\Scholar;
use Faker\Generator as Faker;

$factory->define(Advisor::class, function (Faker $faker) {
    return [
        'scholar_id' => function () {
            return factory(Scholar::class)->create()->id;
        },
        'title' => $faker->title,
        'name' => $faker->name,
        'designation' => $faker->jobTitle,
        'affiliation' => $faker->company,
        'type' => $faker->randomElement(['A', 'C']),
    ];
});
