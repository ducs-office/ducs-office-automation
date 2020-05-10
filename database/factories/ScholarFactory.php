<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\AdvisoryCommitteeMember;
use App\Types\EducationInfo;
use App\Types\Gender;
use App\Types\ReservationCategory;
use App\Types\UserType;
use Faker\Generator as Faker;
use Faker\Provider\bg_BG\PhoneNumber;

$factory->define(Scholar::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => $faker->email(),
        'password' => '$2y$10$BrUcxS6jKnitbT4tRCog2eR00DCkJT.VXOhxRAv2Xxoq.77ow2fV2', // password
        'term_duration' => $faker->numberBetween(4, 6),
        'phone_no' => $faker->phoneNumber(),
        'address' => $faker->address(),
        'gender' => $faker->randomElement(Gender::values()),
        'category' => $faker->randomElement(ReservationCategory::values()),
        'admission_mode' => $faker->randomElement(AdmissionMode::values()),
        'research_area' => $faker->sentence(),
        'supervisor_profile_id' => static function () {
            return factory(SupervisorProfile::class)->create()->id;
        },
        'registration_date' => $faker->date($format = 'Y-m-d', $max = now()),
        'advisory_committee' => function () use ($faker) {
            $cosupervisor = factory(Cosupervisor::class)->create();
            return [
                AdvisoryCommitteeMember::fromExistingCosupervisors($cosupervisor),
                new AdvisoryCommitteeMember('external', [
                    'name' => $faker->name,
                    'designation' => $faker->jobTitle,
                    'affiliation' => $faker->company,
                    'email' => $faker->email,
                    'phone' => $faker->PhoneNumber,
                ]),
            ];
        },
        'education_details' => static function () use ($faker) {
            return array_map(function () use ($faker) {
                return new EducationInfo([
                    'degree' => factory(ScholarEducationDegree::class)->create()->name,
                    'subject' => factory(ScholarEducationSubject::class)->create()->name,
                    'institute' => factory(ScholarEducationInstitute::class)->create()->name,
                    'year' => $faker->date('Y', now()->subYear(1)),
                ]);
            }, range(1, (random_int(1, 4))));
        },
        'cosupervisor_profile_type' => $cosupervisorProfileType = $faker->randomElement([SupervisorProfile::class, Cosupervisor::class]),
        'cosupervisor_profile_id' => function () use ($cosupervisorProfileType) {
            return factory($cosupervisorProfileType)->create()->id;
        },
    ];
});
