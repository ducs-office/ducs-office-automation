<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Course;
use App\Models\Programme;
use Faker\Generator as Faker;

$factory->define(Course::class, static function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('[A-Z0-9]{3}[0-9\-][0-9]{6}'),
        'name' => $faker->words(3, true),
        'type' => $faker->randomElement(array_keys(config('options.courses.types'))),
    ];
});
