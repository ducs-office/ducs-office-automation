<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Cosupervisor::class, function (Faker $faker) {
    return [
        'professor_type' => $faker->randomElement([User::class, null]),
        'professor_id' => static function ($cosupervisor) {
            return $cosupervisor['professor_type']
                ? factory($cosupervisor['professor_type'])->create()->id
                : null;
        },
        'name' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['professor_type'] ? null : $faker->name;
        },
        'email' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['professor_type'] ? null : $faker->email;
        },
        'designation' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['professor_type'] ? null : $faker->sentence;
        },
        'affiliation' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['professor_type'] ? null : $faker->sentence;
        },
    ];
});
