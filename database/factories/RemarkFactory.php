<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Remark;
use App\OutgoingLetter;
use Faker\Generator as Faker;

$factory->define(Remark::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence(),
        'letter_id' => function () {
            return factory(OutgoingLetter::class)->create()->id;
        }
    ];
});
