<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Presentation;
use App\Publication;
use App\Scholar;
use Faker\Generator as Faker;

$factory->define(Presentation::class, function (Faker $faker) {
    return [
        'scholar_id' => $scholar_id = factory(Scholar::class)->create()->id,
        'publication_id' => $publication = factory(Publication::class)->create(['scholar_id' => $scholar_id])->id,
        'city' => $faker->city,
        'country' => $faker->country,
        'date' => $faker->date,
        'event_type' => $faker->randomElement(array_keys(config('options.scholars.academic_details.event_types'))),
        'event_name' => $faker->sentence,
    ];
});
