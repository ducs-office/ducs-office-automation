<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\LetterReminder;
use App\OutgoingLetter;
use Faker\Generator as Faker;

$factory->define(LetterReminder::class, static function (Faker $faker) {
    return [
        'letter_id' => static function () {
            return factory(OutgoingLetter::class)->create()->id;
        },
        'serial_no' => $faker->unique()->regexify('CS\/RM\/[0-9]{4}\/[0-9]{4}'),
    ];
});
