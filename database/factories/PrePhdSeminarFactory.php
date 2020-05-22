<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PrePhdSeminar;
use App\Models\Scholar;
use App\Types\RequestStatus;
use App\Types\ScholarAppealStatus;
use Faker\Generator as Faker;

$factory->define(PrePhdSeminar::class, function (Faker $faker) {
    return [
        'scholar_id' => (factory(Scholar::class)->create()->id),
        'finalized_title' => $faker->word,
        'scheduled_on' => $faker->dateTime,
        'status' => $faker->randomElement(RequestStatus::values()),
    ];
});
