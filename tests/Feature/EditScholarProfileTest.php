<?php

namespace Tests\Feature;

use App\Scholar;
use App\SupervisorProfile;
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
    public function sholar_edit_view_has_a_unique_list_of_supervisors()
    {
        $supervisorProfiles = create(SupervisorProfile::class, 3);
        
        $this->signInScholar(create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfiles[0]->id]));
        
        $viewData = $this->withoutExceptionHandling()
            ->get(route('scholars.profile.edit'))
            ->assertSuccessful()
            ->assertViewIs('scholars.edit')
            ->assertViewHas('supervisorProfiles')
            ->viewData('supervisorProfiles');

        $this->assertCount(3, $viewData);
        $this->assertSame($supervisorProfiles->pluck('id', 'supervisor.name')->toArray(), $viewData->toArray());
    }

    /** @test */
    public function scholar_can_edit_themselves()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $supervisorProfile = create(SupervisorProfile::class);

        $updateDetails = [
            'phone_no' => '12345678',
            'address' => 'new address, new delhi',
            'category' => 'SC',
            'admission_via' => 'NET',
            'profile_picture' => $profilePicture = UploadedFile::fake()->image('picture.jpeg'),
            'enrollment_date' => now()->subMonth(1)->format('Y-m-d'),
            'advisory_committee' => [
                [
                    'title' => 'Mr.',
                    'name' => 'Ashwani Prasad',
                    'designation' => 'Permanent',
                    'affiliation' => 'IP University',
                ],
            ],
            'co_supervisors' => [
                [
                    'title' => 'Dr.',
                    'name' => 'Abdul Kalam',
                    'designation' => 'Permanent',
                    'affiliation' => 'DRDO',
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
        $this->assertEquals($updateDetails['supervisor_profile_id'], $scholar->fresh()->supervisor_profile_id);
        $this->assertEquals($updateDetails['advisory_committee'][0]['title'], $scholar->fresh()->advisory_committee[0]['title']);
        $this->assertEquals($updateDetails['co_supervisors'][0]['title'], $scholar->fresh()->co_supervisors[0]['title']);

        $this->assertEquals(
            'scholar_attachments/profile_picture/' . $profilePicture->hashName(),
            $scholar->fresh()->profilePicture->path
        );
        Storage::assertExists('scholar_attachments/profile_picture/' . $profilePicture->hashName());
    }
}
