<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\EducationInfo;
use App\Types\FundingType;
use App\Types\ReservationCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateScholarProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function scholar_can_update_their_profile()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $subject = create(ScholarEducationSubject::class);
        $degree = create(ScholarEducationDegree::class);
        $institute = create(ScholarEducationInstitute::class);

        $updateDetails = [
            'phone' => '12345678',
            'address' => 'new address, new delhi',
            'category' => ReservationCategory::SC,
            'admission_mode' => AdmissionMode::UGC_NET,
            'funding' => FundingType::NON_NET,
            'profile_picture' => $profilePicture = UploadedFile::fake()->image('picture.jpeg'),
            'registration_date' => now()->subMonth(1)->format('Y-m-d'),
            'research_area' => 'Artificial Intelligence',
            'education_details' => [
                [
                    'degree' => $degree->name,
                    'subject' => $subject->name,
                    'institute' => $institute->name,
                    'year' => '2016',
                ],
            ],
            'enrolment_id' => Str::random(20),
        ];

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), $updateDetails)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $this->assertEquals(1, Scholar::count());

        $freshScholar = $scholar->fresh();
        $this->assertEquals($updateDetails['phone'], $freshScholar->phone);
        $this->assertEquals($updateDetails['address'], $freshScholar->address);
        $this->assertEquals($updateDetails['category'], $freshScholar->category);
        $this->assertEquals($updateDetails['admission_mode'], $freshScholar->admission_mode);
        $this->assertEquals($updateDetails['funding'], $freshScholar->funding);
        $this->assertEquals($updateDetails['registration_date'], $freshScholar->registration_date->format('Y-m-d'));
        $this->assertEquals($updateDetails['research_area'], $freshScholar->research_area);
        $this->assertEquals($updateDetails['enrolment_id'], $freshScholar->enrolment_id);
        $this->assertEquals($updateDetails['education_details'][0]['year'], $freshScholar->education_details[0]->year);
        $this->assertEquals($degree->name, $freshScholar->education_details[0]->degree);

        $this->assertEquals(
            'scholar_attachments/profile_picture/' . $profilePicture->hashName(),
            $freshScholar->profilePicture->path
        );
        Storage::assertExists('scholar_attachments/profile_picture/' . $profilePicture->hashName());
    }

    /** @test */
    public function scholar_education_degree_and_subject_and_institue_tables_have_unique_values_after_any_profile_update()
    {
        $subjects = create(ScholarEducationSubject::class, 3);
        $degrees = create(ScholarEducationDegree::class, 3);
        $institutes = create(ScholarEducationInstitute::class, 3);

        $this->signInScholar(
            $scholar = create(Scholar::class, 1, [
                'education_details' => [],
                'phone' => null,
                'address' => null,
                'category' => null,
                'admission_mode' => null,
                'funding' => null,
                'research_area' => null,
                'enrolment_id' => null,
            ])
        );

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), [
                'education_details' => [
                    [
                        'degree' => $degrees[0]->name,
                        'subject' => $subjects[0]->name,
                        'institute' => $institutes[0]->name,
                        'year' => '2012',
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $freshScholar = $scholar->fresh();
        $this->assertCount(1, $freshScholar->education_details);
        $education = $freshScholar->education_details[0];

        $this->assertEquals(3, ScholarEducationDegree::count());
        $this->assertEquals($degrees[0]->name, $education->degree);

        $this->assertEquals(3, ScholarEducationSubject::count());
        $this->assertEquals($subjects[0]->name, $education->subject);

        $this->assertEquals(3, ScholarEducationInstitute::count());
        $this->assertEquals($institutes[0]->name, $education->institute);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), [
                'education_details' => [
                    [
                        'degree' => 'New Degree',
                        'subject' => 'New Subject',
                        'institute' => 'New Institute',
                        'year' => '2012',
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $freshScholar = $scholar->fresh();
        $this->assertCount(1, $freshScholar->education_details);
        $education = $freshScholar->education_details[0];

        $this->assertEquals(4, ScholarEducationDegree::count());
        $this->assertTrue(ScholarEducationDegree::whereName($education->degree)->exists(), 'new degree wasn\'t created.');

        $this->assertEquals(4, ScholarEducationSubject::count());
        $this->assertTrue(ScholarEducationSubject::whereName($education->subject)->exists(), 'new subject wasn\'t created');

        $this->assertEquals(4, ScholarEducationInstitute::count());
        $this->assertTrue(ScholarEducationInstitute::whereName($education->institute)->exists(), 'new institute wasn\'t created');
    }

    /** @test */
    public function scholar_each_education_array_must_contain_four_elements()
    {
        $this->signInScholar(
            $scholar = create(Scholar::class, 1, [
                'education_details' => [],
                'phone' => null,
                'address' => null,
                'category' => null,
                'admission_mode' => null,
                'research_area' => null,
                'enrolment_id' => null,
            ])
        );

        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.profile.update'), [
                    'education_details' => [
                        [
                            'degree' => 'BSc(H)',
                            'subject' => 'Computer Science',
                            'institute' => 'DU',
                        ],
                    ],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('education_details.0', $e->errors());
        }

        $this->assertCount(0, $scholar->education_details);
    }
}
