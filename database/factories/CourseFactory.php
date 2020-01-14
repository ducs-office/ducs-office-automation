<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Programme;
use App\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('[A-Z0-9]{3}[0-9\-][0-9]{6}'),
        'name' => $faker->words(3, true),
        'type' => $faker->randomElement(config('course.type')),
    ];
});
