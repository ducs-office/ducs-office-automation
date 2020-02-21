<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\College;
use App\Course;
use App\ProgrammeRevision;
use App\Teacher;
use App\TeachingRecord;
use Faker\Generator as Faker;

$factory->define(TeachingRecord::class, function (Faker $faker) {
    return [
        'designation' => $faker->randomElement(array_keys(config('options.teachers.designations'))),
        'college_id' => function () {
            return factory(College::class)->create()->id;
        },
        'teacher_id' => function () {
            return factory(Teacher::class)->create()->id;
        },
        'valid_from' => $faker->date('Y-m-d'),
        'programme_revision_id' => function () {
            return factory(ProgrammeRevision::class)->create()->id;
        },
        'course_id' => function () {
            return factory(Course::class)->create()->id;
        },
        'semester' => $faker->numberBetween(1, 6),
    ];
});