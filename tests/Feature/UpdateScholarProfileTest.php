<?php

namespace Tests\Feature;

use App\Cosupervisor;
use App\Scholar;
use App\ScholarEducationDegree;
use App\ScholarEducationInstitute;
use App\ScholarEducationSubject;
use App\SupervisorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateScholarProfileTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function scholar_can_update_their_profile()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $cosupervisor = create(Cosupervisor::class);
        $supervisorProfile = create(SupervisorProfile::class);
        $subject = create(ScholarEducationSubject::class);
        $degree = create(ScholarducationDegree::class);
        $institute = create(ScholarEducationInstitute::class);

        $updateDetails = [
            'phone_no' => '12345678',
            'address' => 'new address, new delhi',
            'category' => 'SC',
            'admission_via' => 'NET',
            'profile_picture' => $profilePicture = UploadedFile::fake()->image('picture.jpeg'),
            'enrollment_date' => now()->subMonth(1)->format('Y-m-d'),
            'research_area' => 'Artificial Intelligence',
            'advisory_committee' => [
                [
                    'title' => 'Mr.',
                    'name' => 'Ashwani Prasad',
                    'designation' => 'Permanent',
                    'affiliation' => 'IP University',
                ],
            ],
            'education' => [
                [
                    'degree' => $degree->id,
                    'subject' => $subject->id,
                    'institute' => $institute->id,
                    'year' => '2016',
                ],
            ],
        ];

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), $updateDetails)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $this->assertEquals(1, Scholar::count());

        $this->assertEquals($updateDetails['phone_no'], $scholar->fresh()->phone_no);
        $this->assertEquals($updateDetails['address'], $scholar->fresh()->address);
        $this->assertEquals($updateDetails['category'], $scholar->fresh()->category);
        $this->assertEquals($updateDetails['admission_via'], $scholar->fresh()->admission_via);
        $this->assertEquals($updateDetails['enrollment_date'], $scholar->fresh()->enrollment_date);
        $this->assertEquals($updateDetails['research_area'], $scholar->fresh()->research_area);
        $this->assertEquals($updateDetails['advisory_committee'][0]['title'], $scholar->fresh()->advisory_committee[0]['title']);
        $this->assertEquals($updateDetails['education'][0]['year'], $scholar->fresh()->education[0]['year']);

        $this->assertEquals(
            'scholar_attachments/profile_picture/' . $profilePicture->hashName(),
            $scholar->fresh()->profilePicture->path
        );
        Storage::assertExists('scholar_attachments/profile_picture/' . $profilePicture->hashName());
    }

    /** @test */
    public function scholar_education_degree_subject_and_institue_tables_have_unique_values_after_any_profile_update()
    {
        $subjects = create(ScholarEducationSubject::class, 3);
        $degrees = create(ScholarEducationDegree::class, 3);
        $institutes = create(ScholarEducationInstitute::class, 3);

        $this->signInScholar(
            $scholar = create(Scholar::class, 1, [
                'education' => [],
                'phone_no' => null,
                'address' => null,
                'category' => null,
                'admission_via' => null,
                'research_area' => null,
            ])
        );

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), [
                'education' => [
                    [
                        'degree' => $degrees[0]->id,
                        'subject' => $subjects[0]->id,
                        'institute' => $institutes[0]->id,
                        'year' => '2012',
                    ],
                ],
                'advisory_committee' => [
                    [
                        'title' => 'Mr.',
                        'name' => 'Dolittle',
                        'designation' => 'Zoo',
                        'affiliation' => 'Wildlife care',
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $this->assertEquals(count($scholar->education), 1);

        $this->assertEquals(ScholarEducationDegree::count(), 3);
        $this->assertEquals($scholar->education[0]['degree'], $degrees[0]->id);

        $this->assertEquals(ScholarEducationSubject::count(), 3);
        $this->assertEquals($scholar->education[0]['subject'], $subjects[0]->id);

        $this->assertEquals(ScholarEducationInstitute::count(), 3);
        $this->assertEquals($scholar->education[0]['institute'], $institutes[0]->id);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), [
                'education' => [
                    [
                        'degree' => -1,
                        'subject' => -1,
                        'institute' => -1,
                        'year' => '2012',
                    ],
                ],
                'typedSubjects' => [
                    $subjects[0]->name . 'New Subject',
                ],
                'typedDegrees' => [
                    $degrees[0]->name . 'New Degree',
                ],
                'typedInstitutes' => [
                    $institutes[0]->name . 'New Institute',
                ],
                'advisory_committee' => [
                    [
                        'title' => 'Mr.',
                        'name' => 'Dolittle',
                        'designation' => 'Zoo',
                        'affiliation' => 'Wildlife care',
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $this->assertEquals(count($scholar->education), 1);

        $this->assertEquals(ScholarEducationDegree::count(), 4);
        $this->assertEquals($scholar->education[0]['degree'], 4);

        $this->assertEquals(ScholarEducationSubject::count(), 4);
        $this->assertEquals($scholar->education[0]['subject'], 4);

        $this->assertEquals(ScholarEducationInstitute::count(), 4);
        $this->assertEquals($scholar->education[0]['institute'], 4);
    }

    /** @test */
    public function scholar_education_degree_subject_and_institute_table_do_not_contain_duplicates_even_if_the_user_writes_the_same_as_already_present()
    {
        $subjects = create(ScholarEducationSubject::class, 3);
        $degrees = create(ScholarEducationDegree::class, 3);
        $institutes = create(ScholarEducationInstitute::class, 3);

        $this->signInScholar(
            $scholar = create(Scholar::class, 1, [
                'education' => [],
                'phone_no' => null,
                'address' => null,
                'category' => null,
                'admission_via' => null,
                'research_area' => null,
            ])
        );

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), [
                'education' => [
                    [
                        'degree' => -1,
                        'subject' => -1,
                        'institute' => -1,
                        'year' => '2012',
                    ],
                ],
                'typedSubjects' => [
                    $subjects[0]->name,
                ],
                'typedDegrees' => [
                    $degrees[0]->name,
                ],
                'typedInstitutes' => [
                    $institutes[0]->name,
                ],
                'advisory_committee' => [
                    [
                        'title' => 'Mr.',
                        'name' => 'Dolittle',
                        'designation' => 'Zoo',
                        'affiliation' => 'Wildlife care',
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $this->assertEquals(count($scholar->education), 1);

        $this->assertEquals(ScholarEducationSubject::count(), 3);
        $this->assertEquals($scholar->education[0]['subject'], $subjects[0]->id);

        $this->assertEquals(ScholarEducationSubject::count(), 3);
        $this->assertEquals($scholar->education[0]['degree'], $degrees[0]->id);

        $this->assertEquals(ScholarEducationSubject::count(), 3);
        $this->assertEquals($scholar->education[0]['institute'], $institutes[0]->id);
    }

    /** @test */
    public function scholar_each_advisory_committee_array_must_contain_four_elements()
    {
        $this->signInScholar(
            $scholar = create(Scholar::class, 1, [
                'advisory_committee' => [],
                'phone_no' => null,
                'address' => null,
                'category' => null,
                'admission_via' => null,
                'research_area' => null,
            ])
        );

        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.profile.update'), [
                    'advisory_committee' => [
                        [
                            'title' => 'Mr.',
                            'name' => 'Dolittle',
                            'designation' => 'Zoo',
                        ],
                    ],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisory_committee.0', $e->errors());
        }

        $this->assertEquals(count($scholar->advisory_committee), 0);
    }

    /** @test */
    public function scholar_each_education_array_must_contain_four_elements()
    {
        $this->signInScholar(
            $scholar = create(Scholar::class, 1, [
                'education' => [],
                'phone_no' => null,
                'address' => null,
                'category' => null,
                'admission_via' => null,
                'research_area' => null,
            ])
        );

        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.profile.update'), [
                    'education' => [
                        [
                            'degree' => 'BSc(H)',
                            'subject' => 'Computer Science',
                            'institute' => 'DU',
                        ],
                    ],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('education.0', $e->errors());
        }

        $this->assertEquals(count($scholar->education), 0);
    }
}
