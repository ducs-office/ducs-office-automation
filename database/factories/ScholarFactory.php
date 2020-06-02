<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\AdvisoryCommitteeMember;
use App\Types\EducationInfo;
use App\Types\FundingType;
use App\Types\Gender;
use App\Types\RequestStatus;
use App\Types\ReservationCategory;
use App\Types\UserCategory;
use Faker\Generator as Faker;
use Faker\Provider\bg_BG\PhoneNumber;

$factory->define(Scholar::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => $faker->email(),
        'password' => '$2y$10$BrUcxS6jKnitbT4tRCog2eR00DCkJT.VXOhxRAv2Xxoq.77ow2fV2', // password
        'term_duration' => $faker->numberBetween(4, 6),
        'phone' => $faker->phoneNumber(),
        'address' => $faker->address(),
        'gender' => $faker->randomElement(Gender::values()),
        'category' => $faker->randomElement(ReservationCategory::values()),
        'admission_mode' => $faker->randomElement(AdmissionMode::values()),
        'funding' => $faker->randomElement(FundingType::values()),
        'research_area' => $faker->sentence(),
        'registration_date' => $faker->date($format = 'Y-m-d', $max = now()),
        'enrolment_id' => $faker->regexify('[A-Za-z0-9]{30}'),
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
        'proposed_title' => $faker->word,
    ];
});
