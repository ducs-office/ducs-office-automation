<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\OutgoingLetter;
use App\Remark;
use App\User;
use Faker\Generator as Faker;

$factory->define(Remark::class, static function (Faker $faker) {
    return [
        'description' => $faker->sentence(),
        'user_id' => static function () {
            return factory(User::class)->create()->id;
        },
        'remarkable_id' => static function () {
            return factory(OutgoingLetter::class)->create()->id;
        },
        'remarkable_type' => OutgoingLetter::class,
    ];
});
