<?php

namespace Tests\Feature;

use App\Scholar;
use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EditScholarProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_edit_themselves()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $updateDetails = [
            'phone_no' => '12345678',
            'address' => 'new address, new delhi',
            'category' => 'SC',
            'admission_via' => 'NET',
            'profile_picture' => $profilePicture = UploadedFile::fake()->image('picture.jpeg'),
            'advisors' => [
                'advisory_committee' => [
                    [
                        'title' => 'Mr.',
                        'name' => 'Ashwani Prasad',
                        'designation' => 'Permanent',
                        'affiliation' => 'IP University',
                    ],
                    [
                        'title' => 'Dr.',
                        'name' => 'Abdul Kalam',
                        'designation' => 'Permanent',
                        'affiliation' => 'DRDO',
                    ],
                    [
                        'title' => 'Mrs.',
                        'name' => 'Surbhi Mahajan',
                        'designation' => 'Permanent',
                        'affiliation' => 'Jammu University',
                    ],
                ],
                'co_supervisors' => [
                    [
                        'title' => 'Mrs.',
                        'name' => 'Reena Prasad',
                        'designation' => 'Permanent',
                        'affiliation' => 'IP University',
                    ],
                    [
                        'title' => 'Dr.',
                        'name' => 'Zakir Hussain',
                        'designation' => 'Permanent',
                        'affiliation' => 'DRDO',
                    ],
                ],
            ],
        ];

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.update'), $updateDetails)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile updated successfully!');

        $this->assertEquals(1, Scholar::count());

        $this->assertEquals($updateDetails['phone_no'], $scholar->profile->fresh()->phone_no);
        $this->assertEquals($updateDetails['address'], $scholar->profile->fresh()->address);
        $this->assertEquals($updateDetails['category'], $scholar->profile->fresh()->category);
        $this->assertEquals($updateDetails['admission_via'], $scholar->profile->fresh()->admission_via);
        $this->assertEquals($updateDetails['advisors']['advisory_committee'][0]['title'], $scholar->profile->advisoryCommittee->first()->title);
        $this->assertEquals($updateDetails['advisors']['co_supervisors'][0]['title'], $scholar->profile->coSupervisors->first()->title);

        $this->assertEquals(
            'scholar_attachments/profile_picture/' . $profilePicture->hashName(),
            $scholar->profile->fresh()->profilePicture->path
        );
        Storage::assertExists('scholar_attachments/profile_picture/' . $profilePicture->hashName());
    }

    // /** @test */
    // public function request_validates_size_of_advisory_committee_is_either_3_or_4()
    // {
    //     $this->signInScholar();

    //     $updateDetails = [
    //         'advisory_committee' => [
    //             [
    //                 'title' => 'Mr.',
    //                 'name' => 'Ashwani Prasad',
    //                 'designation' => 'Permanent',
    //                 'affiliation' => 'IP University',
    //             ],
    //             [
    //                 'title' => 'Dr.',
    //                 'name' => 'Abdul Kalam',
    //                 'designation' => 'Permanent',
    //                 'affiliation' => 'DRDO',
    //             ],
    //         ],
    //     ];

    //     try {
    //         $this->withoutExceptionHandling()
    //             ->patch(route('scholars.profile.update'), $updateDetails);

    //         $this->fail('advisory committee array size was not validated');
    //     } catch (ValidationException $e) {
    //         $this->assertArrayHasKey('advisory_committee', $e->errors());
    //     }
    // }
}
