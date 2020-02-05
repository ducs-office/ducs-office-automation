<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\College;
use App\Teacher;
use App\TeacherProfile;
use Faker\Generator as Faker;

$factory->define(TeacherProfile::class, function (Faker $faker) {
    return [
        'phone_no' => $faker->regexify('[6-9][0-9]{9}'),
        'address' => $faker->address,
        'designation' => $faker->randomElement(array_keys(config('options.teachers.designations'))),
        'college_id' => function () {
            return factory(College::class)->create()->id;
        },
    ];
});
