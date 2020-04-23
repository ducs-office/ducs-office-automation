<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use Faker\Generator as Faker;

$factory->define(Presentation::class, function (Faker $faker) {
    return [
        'scholar_id' => $scholar = factory(Scholar::class)->create()->id,
        'publication_id' => factory(Publication::class)->create([
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar,
        ])->id,
        'city' => $faker->city,
        'country' => $faker->country,
        'date' => $faker->date,
        'event_type' => $faker->randomElement(array_keys(config('options.scholars.academic_details.event_types'))),
        'event_name' => $faker->sentence,
    ];
});
