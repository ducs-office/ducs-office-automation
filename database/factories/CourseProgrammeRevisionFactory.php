<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Course;
use App\CourseProgrammeRevision;
use App\ProgrammeRevision;
use Faker\Generator as Faker;

$factory->define(CourseProgrammeRevision::class, function (Faker $faker) {
    return [
        'programme_revision_id' => function () {
            return factory(ProgrammeRevision::class)->create()->id;
        },
        'course_id' => function () {
            return factory(Course::class)->create()->id;
        },
        'semester' => $faker->numberBetween(1, 6),
    ];
});
