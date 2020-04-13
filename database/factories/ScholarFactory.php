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
        'phone_no' => $faker->phoneNumber(),
        'address' => $faker->address(),
        'gender' => $faker->randomElement(Gender::values()),
        'category' => $faker->randomElement(ReservationCategory::values()),
        'admission_mode' => $faker->randomElement(AdmissionMode::values()),
        'research_area' => $faker->sentence(),
        'supervisor_profile_id' => static function () {
            return factory(SupervisorProfile::class)->create()->id;
        },
        'enrollment_date' => $faker->date($format = 'Y-m-d', $max = now()),
        'advisory_committee' => function () use ($faker) {
            $faculty_teacher = factory(User::class)->create([
                'type' => UserType::FACULTY_TEACHER,
            ]);
            return [
                AdvisoryCommitteeMember::fromFacultyTeacher($faculty_teacher),
                new AdvisoryCommitteeMember('external', [
                    'name' => $faker->name,
                    'designation' => $faker->jobTitle,
                    'affiliation' => $faker->company,
                    'email' => $faker->email,
                    'phone' => $faker->PhoneNumber,
                ]),
            ];
        },
        'education' => static function () use ($faker) {
            $x = random_int(1, 4);
            $data = [];
            for ($i = 1; $i <= $x; $i++) {
                $degree = factory(ScholarEducationDegree::class)->create();
                $subject = factory(ScholarEducationSubject::class)->create();
                $institute = factory(ScholarEducationInstitute::class)->create();

                array_push($data, [
                    'degree' => $degree->id,
                    'subject' => $subject->id,
                    'institute' => $institute->id,
                    'year' => $faker->date('Y', now()->subYear(1)),
                ]);
            }
            return $data;
        },
        'cosupervisor_id' => function () {
            return factory(Cosupervisor::class)->create()->id;
        },
    ];
});
