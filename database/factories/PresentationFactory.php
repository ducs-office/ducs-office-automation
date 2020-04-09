<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Presentation;
use App\Publication;
use Faker\Generator as Faker;

$factory->define(Presentation::class, function (Faker $faker) {
    return [
        'publication_id' => factory(Publication::class)->create()->id,
        'city' => $faker->city,
        'country' => $faker->country,
        'date' => $faker->date,
        'event_type' => $faker->randomElement(array_keys(config('options.scholars.academic_details.event_types'))),
        'event_name' => $faker->sentence,
    ];
});
