<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(CoAuthor::class, function (Faker $faker) {
    return [
        'publication_id' => static function () {
            return factory(Publication::class);
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

$factory->state(CoAuthor::class, 'others', [
    'user_id' => null,
    'type' => 0,
]);

$factory->state(CoAuthor::class, 'is_supervisor', [
    'user_id' => static function () {
        return factory(User::class);
    },
    'name' => '',
    'type' => 1,
    'noc_path' => '',
]);

$factory->state(CoAuthor::class, 'is_cosupervisor', [
    'user_id' => static function () {
        return factory(User::class);
    },
    'name' => '',
    'type' => 2,
    'noc_path' => '',
]);
