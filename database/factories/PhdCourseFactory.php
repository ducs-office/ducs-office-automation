<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PhdCourse;
use Faker\Generator as Faker;

$factory->define(PhdCourse::class, function (Faker $faker) {
    return [
        'code' => $faker->numerify('RSC0###'),
        'type' => $faker->randomElement(array_keys(config('options.phd_courses.types'))),
        'name' => $faker->words(4, true),
    ];
});
