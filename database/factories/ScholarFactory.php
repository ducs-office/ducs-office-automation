<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Cosupervisor;
use App\Scholar;
use App\ScholarEducationDegree;
use App\ScholarEducationInstitute;
use App\ScholarEducationSubject;
use App\SupervisorProfile;
use Faker\Generator as Faker;

$factory->define(Scholar::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => $faker->email(),
        'password' => '$2y$10$BrUcxS6jKnitbT4tRCog2eR00DCkJT.VXOhxRAv2Xxoq.77ow2fV2', // password
        'phone_no' => $faker->phoneNumber(),
        'address' => $faker->address(),
        'category' => $faker->randomElement(array_keys(config('options.scholars.categories'))),
        'admission_via' => $faker->randomElement(array_keys(config('options.scholars.admission_criterias'))),
        'research_area' => $faker->sentence(),
        'gender' => $faker->randomElement(array_keys(config('options.scholars.genders'))),
        'supervisor_profile_id' => static function () {
            return factory(SupervisorProfile::class)->create()->id;
        },
        'enrollment_date' => $faker->date($format = 'Y-m-d', $max = now()),
        'advisory_committee' => static function () use ($faker) {
            $x = random_int(1, 4);
            $data = [];
            for ($i = 1; $i <= $x; $i++) {
                array_push($data, [
                    'title' => $faker->title,
                    'name' => $faker->name,
                    'designation' => $faker->jobTitle,
                    'affiliation' => $faker->company,
                ]);
            }
            return $data;
        },
        'education' => static function () use ($faker) {
            $x = random_int(1, 4);
            $data = [];
            for ($i = 1; $i <= $x; $i++) {
                $degree = factory(ScholarEducationDegree::class)->create()->id;
                $subject = factory(ScholarEducationSubject::class)->create()->id;
                $institute = factory(ScholarEducationInstitute::class)->create()->id;

                array_push($data, [
                    'degree' => $degree,
                    'subject' => $subject,
                    'institute' => $institute,
                    'year' => $faker->date('Y', now()->subYear(1)),
                ]);
            }
            return $data;
        },
        'cosupervisor_id' => factory(Cosupervisor::class)->create()->id,
    ];
});
