<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\ProgressReport;
use App\Models\Scholar;
use App\Types\ProgressReportRecommendation;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(ProgressReport::class, function (Faker $faker) {
    return [
        'scholar_id' => factory(Scholar::class)->create()->id,
        'recommendation' => $faker->randomElement(ProgressReportRecommendation::values()),
        'date' => $faker->date('Y-m-d'),
        'path' => function () {
            Storage::fake();

            return UploadedFile::fake()
                    ->create('document.pdf', 100, 'application/pdf')
                    ->store('progress_reports');
        },
    ];
});
