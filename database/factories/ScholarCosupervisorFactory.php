<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pivot\ScholarCosupervisor;
use App\Models\Scholar;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ScholarCosupervisor::class, function (Faker $faker) {
    return [
        'scholar_id' => function () {
            return factory(Scholar::class);
        },
        'user_id' => function () {
            return factory(User::class)->states('cosupervisor');
        },
    ];
});
