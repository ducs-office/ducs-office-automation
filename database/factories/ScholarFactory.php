<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\SupervisorProfile;
use App\Models\User;
use Faker\Generator as Faker;
use Faker\Provider\bg_BG\PhoneNumber;

$factory->define(Scholar::class, function (Faker $faker) {
    $faculty_teacher = factory(User::class)->create(['category' => 'faculty_teacher']);

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
        'advisory_committee' => [
            'faculty_teacher' => $faculty_teacher->name,
            'external' => [
                'name' => $faker->name,
                'designation' => $faker->jobTitle,
                'affiliation' => $faker->company,
                'email' => $faker->email,
                'phone_no' => $faker->PhoneNumber,
            ],
        ],
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
        'cosupervisor_id' => factory(Cosupervisor::class)->create()->id,
    ];
});
