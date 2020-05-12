<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ExternalAuthority;
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
use Faker\Generator as Faker;

$factory->define(Cosupervisor::class, function (Faker $faker) {
    return [
        'person_type' => $faker->randomElement([User::class, ExternalAuthority::class]),
        'person_id' => function ($cosupervisor) use ($faker) {
            return factory($cosupervisor['person_type'])->create()->id;
        },
    ];
});
