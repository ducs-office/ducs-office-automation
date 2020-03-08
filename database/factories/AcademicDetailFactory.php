<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\AcademicDetail;
use App\Scholar;
use Faker\Generator as Faker;

$factory->define(AcademicDetail::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['publication', 'presentation']),
        'authors' => static function () use ($faker) {
            $authors = array_fill(0, random_int(1, 10), 'NULL');
            return array_map(function () use ($faker) {
                return $faker->name;
            }, $authors);
        },
        'title' => $faker->sentence,
        'conference' => $faker->sentence,
        'volume' => $faker->numberBetween(1, 20),
        'publisher' => $faker->company,
        'page_numbers' => static function () use ($faker) {
            $from = random_int(1, 10000);
            $pages = random_int(1, 10000);
            return ['from' => $from, 'to' => $from + $pages];
        },
        'date' => $faker->date,
        'number' => random_int(10000, 100000),
        'venue' => ['city' => $faker->city, 'Country' => $faker->country],
        'indexed_in' => static function () use ($faker) {
            $size = random_int(1, 3);
            $indexed_in = array_fill(0, $size, 'NULL');
            return array_map(function () use ($faker) {
                return $faker->randomElement(array_keys(config('options.scholars.academic_details.indexed_in')));
            }, $indexed_in);
        },
        'scholar_id' => static function () {
            return factory(Scholar::class)->create()->id;
        },
    ];
});
