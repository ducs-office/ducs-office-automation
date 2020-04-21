<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\ScholarEducationDegree;
use Faker\Generator as Faker;

$factory->define(ScholarEducationDegree::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
    ];
});
