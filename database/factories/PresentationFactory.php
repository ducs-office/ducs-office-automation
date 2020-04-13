<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use App\Types\PresentationEventType;
use Faker\Generator as Faker;

$factory->define(Presentation::class, function (Faker $faker) {
    return [
        'scholar_id' => function () {
            return factory(Scholar::class)->create()->id;
        },
        'publication_id' => function () {
            return factory(Publication::class)->create()->id;
        },
        'city' => $faker->city,
        'country' => $faker->country,
        'date' => $faker->date,
        'event_type' => $faker->randomElement(PresentationEventType::values()),
        'event_name' => $faker->sentence,
    ];
});
