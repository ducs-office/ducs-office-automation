<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\College;
use App\PastTeachersProfile;
use App\Teacher;
use Faker\Generator as Faker;

$factory->define(PastTeachersProfile::class, function (Faker $faker) {
    return [
        'teacher_id' => factory(Teacher::class)->create()->id,
        'designation' => $faker->randomElement(array_keys(config('options.teachers.designations'))),
        'college_id' => function () {
            return factory(College::class)->create()->id;
        },
        'valid_from' => $faker->date(),
    ];
});
