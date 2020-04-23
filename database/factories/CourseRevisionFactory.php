<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Course;
use App\Models\CourseRevision;
use Faker\Generator as Faker;

$factory->define(CourseRevision::class, static function (Faker $faker) {
    return [
        'revised_at' => $faker->dateTimeBetween('-10 years'),
        'course_id' => static function () {
            return factory(Course::class)->create()->id;
        },
    ];
});
