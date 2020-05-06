<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Cosupervisor::class, function (Faker $faker) {
    return [
        'professor_type' => $professorClass = $faker->randomElement([User::class, Teacher::class, null]),
        'professor_id' => static function () use ($professorClass) {
            return $professorClass ? factory($professorClass)->create()->id : null;
        },
        'name' => $professorClass ? null : $faker->name,
        'email' => $professorClass ? null : $faker->email,
        'designation' => $professorClass ? null : $faker->sentence,
        'affiliation' => $professorClass ? null : $faker->sentence,
    ];
});
