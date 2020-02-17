<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Course;
use App\CourseRevision;
use Faker\Generator as Faker;

$factory->define(CourseRevision::class, function (Faker $faker) {
    return [
        'revised_at' => $faker->dateTimeBetween('-10 years'),
        'course_id' => function () {
            return factory(Course::class)->create()->id;
        },
    ];
});
