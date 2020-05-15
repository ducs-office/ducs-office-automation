<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\College;
use App\Models\Course;
use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\ProgrammeRevision;
use App\Models\Teacher;
use App\Models\TeachingRecord;
use App\Models\User;
use App\Types\Designation;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Faker\Generator as Faker;

$factory->define(TeachingRecord::class, function (Faker $faker) {
    return [
        'valid_from' => $faker->date('Y-m-d'),
        'teacher_id' => function () use ($faker) {
            return factory(User::class)->create([
                'category' => UserCategory::COLLEGE_TEACHER,
            ])->id;
        },
        'status' => function ($record) {
            return User::find($record['teacher_id'])->status;
        },
        'designation' => function ($record) {
            return User::find($record['teacher_id'])->designation;
        },
        'college_id' => function ($record) {
            return User::find($record['teacher_id'])->college_id;
        },
        'programme_revision_id' => factory(ProgrammeRevision::class),
        'course_id' => factory(Course::class),
        'semester' => function ($record) use ($faker) {
            return CourseProgrammeRevision::firstOrCreate([
                'course_id' => $record['course_id'],
                'programme_revision_id' => $record['programme_revision_id'],
            ], ['semester' => $faker->numberBetween(1, 6)])->semester;
        },
    ];
});
