<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\User;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(Publication::class, function (Faker $faker) {
    return [
        'type' => $type = $faker->randomElement(PublicationType::values()),
        'paper_title' => $faker->sentence,
        'name' => $faker->sentence,
        'volume' => $faker->numberBetween(1, 20),
        'page_numbers' => static function () use ($faker) {
            $from = random_int(1, 10000);
            $pages = random_int(1, 10000);
            return [$from, $from + $pages];
        },
        'date' => $faker->date,
        'indexed_in' => static function () use ($faker) {
            $size = random_int(1, 3);
            $indexed_in = array_fill(0, $size, 'NULL');
            return array_map(function () use ($faker) {
                return $faker->randomElement(CitationIndex::values());
            }, $indexed_in);
        },
        'author_type' => $type = $faker->randomElement([Scholar::class, User::class]),
        'author_id' => function ($publication) {
            return factory($publication['author_type'])->create()->id;
        },
        'number' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::JOURNAL
                ? $faker->randomNumber(2) : null;
        },
        'publisher' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::JOURNAL
                ? $faker->name : null;
        },
        'city' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::CONFERENCE
                ? $faker->city : null;
        },
        'country' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::CONFERENCE
                ? $faker->country : null;
        },
        'is_published' => $faker->randomElement([true, false]),
        'document_path' => static function () {
            Storage::fake();
            return UploadedFile::fake()
                ->create('file.pdf', 100, 'application/pdf')
                ->store('publications');
        },
    ];
});
