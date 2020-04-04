<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\AdvisoryMeeting;
use App\Scholar;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(AdvisoryMeeting::class, function (Faker $faker) {
    return [
        'date' => $faker->date,
        'scholar_id' => function () {
            return factory(Scholar::class)->create()->id;
        },
        'minutes_of_meeting_path' => function () {
            return UploadedFile::fake()->create('file.pdf', 20, 'document/pdf')->store('advisory_meetings');
        },
    ];
});
