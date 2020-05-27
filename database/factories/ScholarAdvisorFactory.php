<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pivot\ScholarAdvisor;
use App\Models\Scholar;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ScholarAdvisor::class, function (Faker $faker) {
    return [
        'user_id' => function ($advisor) {
            return factory(User::class)->states('external');
        },
        'scholar_id' => function ($advisor) {
            return factory(Scholar::class);
        },
    ];
});
