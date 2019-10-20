<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\LetterReminder;
use App\OutgoingLetter;
use Faker\Generator as Faker;

$factory->define(LetterReminder::class, function (Faker $faker) {
    return [
            'letter_id' => function () {
                return factory(OutgoingLetter::class)->create()->id;
            },
            'serial_no' => $faker->unique()->regexify('CS\/RM\/[0-9]{4}\/[0-9]{4}')
    ];
});
