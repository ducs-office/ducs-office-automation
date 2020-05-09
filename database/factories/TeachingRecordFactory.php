<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\College;
use App\Models\Course;
use App\Models\ProgrammeRevision;
use App\Models\Teacher;
use App\Models\TeachingRecord;
use App\Models\User;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Faker\Generator as Faker;

$factory->define(TeachingRecord::class, function (Faker $faker) {
    return [
        'designation' => $faker->randomElement(TeacherStatus::values()),
        'college_id' => factory(College::class),
        'teacher_id' => function () use ($faker) {
            return factory(User::class)->create([
                'category' => $faker->randomElement([
                    UserCategory::FACULTY_TEACHER,
                    UserCategory::COLLEGE_TEACHER,
                ]),
            ])->id;
        },
        'valid_from' => $faker->date('Y-m-d'),
        'programme_revision_id' => factory(ProgrammeRevision::class),
        'course_id' => factory(Course::class),
        'semester' => $faker->numberBetween(1, 6),
    ];
});
