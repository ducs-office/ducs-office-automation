<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Model;
use App\Programme;
use App\ProgrammeRevision;
use Faker\Generator as Faker;

$factory->define(ProgrammeRevision::class, static function (Faker $faker) {
    return [
        'programme_id' => static function () {
            return factory(Programme::class)->create()->id;
        },
        'revised_at' => $faker->dateTime(),
    ];
});
