<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Scholar;
use App\ScholarProfile;
use Faker\Generator as Faker;

$factory->define(ScholarProfile::class, function (Faker $faker) {
    return [
        'phone_no' => $faker->phoneNumber(),
        'address' => $faker->address(),
        'category' => $faker->randomElement(array_keys(config('options.scholars.categories'))),
        'admission_via' => $faker->randomElement(array_keys(config('options.scholars.admission_via'))),
    ];
});
