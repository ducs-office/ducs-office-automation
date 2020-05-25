<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Scholar;
use App\Models\ScholarExaminer;
use App\Types\RequestStatus;
use Faker\Generator as Faker;

$factory->define(ScholarExaminer::class, function (Faker $faker) {
    return [
        'scholar_id' => factory(Scholar::class)->create()->id,
        'recommended_on' => $faker->date,
        'approved_on' => $faker->date,
        'status' => $faker->randomElement(RequestStatus::values()),
    ];
});
