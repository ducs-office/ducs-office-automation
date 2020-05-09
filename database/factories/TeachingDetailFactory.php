<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Course;
use App\Models\ProgrammeRevision;
use App\Models\TeachingDetail;
use App\Models\User;
use App\Types\UserCategory;
use Faker\Generator as Faker;

$factory->define(TeachingDetail::class, function (Faker $faker) {
    return [
        'teacher_id' => function () {
            return factory(User::class)->create([
                'category' => UserCategory::COLLEGE_TEACHER,
            ])->id;
        },
        'semester' => $faker->numberBetween(1, 6),
        'programme_revision_id' => factory(ProgrammeRevision::class),
        'course_id' => function ($detail) {
            $course = factory(Course::class)->create();
            $course->programmeRevisions()
                ->attach($detail['programme_revision_id'], [
                    'semester' => $detail['semester'],
                ]);
            return $course->id;
        },
    ];
});
