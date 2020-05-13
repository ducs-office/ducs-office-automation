<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Scholar;
use App\Models\ScholarAdvisor;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ScholarAdvisor::class, function (Faker $faker) {
    return [
        'advisor_type' => $faker->randomElement([User::class, Scholar::class]),
        'advisor_id' => function ($advisor) {
            return factory($advisor['advisor_type'])->create()->id;
        },
    ];
});
