<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Types\ProgressReportRecommendation;
use App\Types\ScholarDocumentType;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(ScholarDocument::class, function (Faker $faker) {
    return [
        'scholar_id' => factory(Scholar::class)->create()->id,
        'type' => $type = $faker->randomElement(ScholarDocumentType::values()),
        'path' => function () {
            Storage::fake();

            return UploadedFile::fake()
                    ->create('document.pdf', 100, 'application/pdf')
                    ->store('scholar_documents');
        },
        'description' => ($type === ScholarDocumentType::PROGRESS_REPORT) ?
                 $faker->randomElement(ProgressReportRecommendation::values()) :
                 $faker->sentence,
        'date' => $faker->date('Y-m-d'),
    ];
});
