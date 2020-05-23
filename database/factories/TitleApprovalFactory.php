<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Scholar;
use App\Models\TitleApproval;
use App\Types\RequestStatus;
use Faker\Generator as Faker;

$factory->define(TitleApproval::class, function (Faker $faker) {
    return [
        'scholar_id' => (factory(Scholar::class)->create()->id),
        'recommended_title' => $faker->word,
        'status' => $faker->randomElement(RequestStatus::values()),
    ];
});
