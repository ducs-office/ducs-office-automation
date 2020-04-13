<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\College;
use App\Models\TeacherProfile;
use App\Types\TeacherStatus;
use Faker\Generator as Faker;

$factory->define(TeacherProfile::class, static function (Faker $faker) {
    return [
        'phone_no' => $faker->regexify('[6-9][0-9]{9}'),
        'address' => $faker->address,
        'designation' => $faker->randomElement(TeacherStatus::values()),
        'college_id' => static function () {
            return factory(College::class)->create()->id;
        },
    ];
});
