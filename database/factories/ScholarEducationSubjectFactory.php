<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\ScholarEducationSubject;
use Faker\Generator as Faker;

$factory->define(ScholarEducationSubject::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(2),
    ];
});
