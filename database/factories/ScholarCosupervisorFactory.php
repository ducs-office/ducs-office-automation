<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ExternalAuthority;
use App\Models\Scholar;
use App\Models\ScholarCosupervisor;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ScholarCosupervisor::class, function (Faker $faker) {
    return [
        'scholar_id' => factory(Scholar::class),
        'person_type' => $faker->randomElement([User::class, ExternalAuthority::class]),
        'person_id' => function ($cosup) {
            return factory($cosup['person_type'])->states('cosupervisor')->create()->id;
        },
    ];
});

$factory->state(ScholarCosupervisor::class, 'user', [
    'person_type' => User::class,
    'person_id' => function () {
        return factory(User::class)->state('cosupervisor')->create();
    },
]);

$factory->state(ScholarCosupervisor::class, 'external', [
    'person_type' => ExternalAuthority::class,
    'person_id' => function () {
        return factory(ExternalAuthority::class)->state('cosupervisor')->create();
    },
]);
