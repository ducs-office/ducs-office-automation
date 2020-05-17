<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Faker\Generator as Faker;

$factory->define(ScholarAppeal::class, function (Faker $faker) {
    return [
        'scholar_id' => (factory(Scholar::class)->create()->id),
        'proposed_title' => $faker->word,
        'status' => $faker->randomElement(ScholarAppealStatus::values()),
        'type' => $faker->randomElement(ScholarAppealTypes::values()),
    ];
});
