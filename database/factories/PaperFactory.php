<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use App\Paper;
use Faker\Generator as Faker;

$factory->define(Paper::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->regexify('[A-Z0-9]{3}[0-9\-][0-9]{6}'),
        'name' => $faker->words(3, true),
        'course_id' => function() {
            return factory(Course::class)->create()->id;
        }
    ];
});
