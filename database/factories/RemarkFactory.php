<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Remark;
use App\OutgoingLetter;
use App\User;
use Faker\Generator as Faker;

$factory->define(Remark::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence(),
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'remarkable_id' => function () {
            return factory(OutgoingLetter::class)->create()->id;
        },
        'remarkable_type' => OutgoingLetter::class
    ];
});
