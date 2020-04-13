<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PhdCourse;
use App\Types\PrePhdCourseType;
use Faker\Generator as Faker;

$factory->define(PhdCourse::class, function (Faker $faker) {
    return [
        'code' => $faker->numerify('RSC0###'),
        'type' => $faker->randomElement(PrePhdCourseType::values()),
        'name' => $faker->words(4, true),
    ];
});
