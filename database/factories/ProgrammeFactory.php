<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Programme;
use App\Types\ProgrammeType;
use Faker\Generator as Faker;

$factory->define(Programme::class, static function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('[A-Z0-9]{3}[0-9\-][0-9]{6}'),
        'name' => $faker->words(3, true),
        'wef' => $faker->dateTime(),
        'duration' => $faker->numberBetween(2, 4),
        'type' => $faker->randomElement(ProgrammeType::values()),
    ];
});
