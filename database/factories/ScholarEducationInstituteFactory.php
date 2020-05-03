<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\ScholarEducationInstitute;
use Faker\Generator as Faker;

$factory->define(ScholarEducationInstitute::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(2),
    ];
});
