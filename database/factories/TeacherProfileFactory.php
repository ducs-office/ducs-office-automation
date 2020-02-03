<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\College;
use App\Teacher;
use App\TeacherProfile;
use Faker\Generator as Faker;

$factory->define(TeacherProfile::class, function (Faker $faker) {
    return [
        'phone_no' => $faker->phoneNumber,
        'address' => $faker->address,
        'designation' => $faker->randomElement(array_keys(config('options.teachers.designation'))),
        'ifsc' => $faker->text(12),
        'account_no' => $faker->bankAccountNumber,
        'bank_name' => $faker->name,
        'bank_branch' => $faker->address,
        'college_id' => function () {
            return factory(College::class)->create()->id;
        },
    ];
});
