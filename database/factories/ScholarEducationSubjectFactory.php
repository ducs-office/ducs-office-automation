<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\ScholarEducationSubject;
use Faker\Generator as Faker;

$factory->define(ScholarEducationSubject::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
    ];
});
