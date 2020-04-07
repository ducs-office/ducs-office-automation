<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Leave;
use App\LeaveStatus;
use App\Scholar;
use Faker\Generator as Faker;

$factory->define(Leave::class, function (Faker $faker) {
    return [
        'from' => $faker->date('Y-m-d'),
        'to' => $faker->date('Y-m-d'),
        'reason' => $faker->sentence,
        'status' => LeaveStatus::APPLIED,
        'scholar_id' => function () {
            return factory(Scholar::class)->create()->id;
        },
    ];
});
