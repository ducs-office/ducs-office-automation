<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Leave;
use App\Models\LeaveStatus;
use App\Models\Scholar;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(Leave::class, function (Faker $faker) {
    return [
        'from' => $faker->date('Y-m-d'),
        'to' => $faker->date('Y-m-d'),
        'reason' => $faker->sentence,
        'status' => LeaveStatus::APPLIED,
        'application_path' => function () {
            Storage::fake();
            return UploadedFile::fake()
                ->create('file.pdf', 100, 'application/pdf')
                ->store('scholar_leaves');
        },
        'scholar_id' => function () {
            return factory(Scholar::class)->create()->id;
        },
    ];
});
