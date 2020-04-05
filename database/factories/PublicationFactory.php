<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Publication;
use App\Scholar;
use Faker\Generator as Faker;

$factory->define(Publication::class, function (Faker $faker) {
    return [
        'scholar_id' => factory(Scholar::class)->create()->id,
    ];
});
