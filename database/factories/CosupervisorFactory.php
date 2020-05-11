<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
use Faker\Generator as Faker;

$factory->define(Cosupervisor::class, function (Faker $faker) {
    return [
        'user_id' => function () use ($faker) {
            return factory(User::class)->create([
                'category' => $faker->randomElement([
                    UserCategory::COLLEGE_TEACHER,
                    UserCategory::FACULTY_TEACHER,
                ]),
            ]);
        },
        'name' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['user_id'] ? null : $faker->name;
        },
        'email' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['user_id'] ? null : $faker->email;
        },
        'designation' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['user_id'] ? null : $faker->sentence;
        },
        'affiliation' => function ($cosupervisor) use ($faker) {
            return $cosupervisor['user_id'] ? null : $faker->sentence;
        },
    ];
});
