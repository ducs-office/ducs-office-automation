<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CoAuthor;
use App\Models\Publication;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(CoAuthor::class, function (Faker $faker) {
    return [
        'publication_id' => static function () {
            return factory(Publication::class)->id;
        },
        'name' => $faker->name,
        'noc_path' => static function () {
            Storage::fake();

            return UploadedFile::fake()
                    ->create('noc.pdf', 100, 'application/pdf')
                    ->store('publications/co_authors_noc');
        },
    ];
});
