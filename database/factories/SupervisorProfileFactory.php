<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\SupervisorProfile;
use App\Teacher;
use App\User;
use Faker\Generator as Faker;

$factory->define(SupervisorProfile::class, function (Faker $faker) {
    $temp = rand(0, 1);

    if ($temp == 1) {
        $teacher = factory(Teacher::class)->create();
        return [
            'supervisor_id' => $teacher->id,
            'supervisor_type' => Teacher::class,
        ];
    } else {
        $faculty = factory(User::class)->create(['category' => 'faculty_teacher']);
        return[
            'supervisor_id' => $faculty->id,
            'supervisor_type' => User::class,
        ];
    }
});
