<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\ScholarEducationInstitute;
use Faker\Generator as Faker;

$factory->define(ScholarEducationInstitute::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
    ];
});
