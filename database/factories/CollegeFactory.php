<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\College;
use App\Programme;
use Faker\Generator as Faker;

$factory->define(College::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('DU-[A-Z]{3,5}-[0-9]{2}'),
        'name' => $faker->unique()->words(4, true),
        'principal_name' => $faker->name,
        'principal_phones' => [$faker->regexify('[9876][0-9]{9}'), $faker->regexify('[9876][0-9]{9}')],
        'principal_emails' => [$faker->safeEmail, $faker->safeEmail],
        'address' => $faker->address,
        'website' => $faker->url,
    ];
});
