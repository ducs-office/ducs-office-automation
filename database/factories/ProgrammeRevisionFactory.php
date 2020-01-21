<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\ProgrammeRevision;
use Faker\Generator as Faker;

$factory->define(ProgrammeRevision::class, function (Faker $faker) {
    return [
        'programme_id' => function () {
            return factory(ProgrammeRevision::class)->create()->id;
        },
        'revised_at' => $faker->dateTime(),
    ];
});
