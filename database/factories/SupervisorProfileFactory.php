<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
use Faker\Generator as Faker;

$factory->define(SupervisorProfile::class, function (Faker $faker) {
    return [
        'supervisor_id' => function () use ($faker) {
            return factory(User::class)->create([
                'category' => $faker->randomElement([
                    UserCategory::FACULTY_TEACHER,
                    UserCategory::COLLEGE_TEACHER,
                ]),
            ])->id;
        },
    ];
});
