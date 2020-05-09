<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Types\AdvisoryCommitteeMember;
use App\Types\UserCategory;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupervisorManagesScholarAdvisoryCommiteeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_advisory_committee_can_be_edited_by_their_supervisor()
    {
        $this->signIn($faculty = create(User::class, 1, ['category' => UserCategory::FACULTY_TEACHER]));

        $supervisor = $faculty->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisor->id,
        ]);
        $otherSupervisorProfile = create(SupervisorProfile::class);
        $otherCosupervisor = create(Cosupervisor::class);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisory_committee.update', [
                'scholar' => $scholar,
            ]), [
                'committee' => [
                    $existingCosupervisor = [
                        'type' => 'existing_cosupervisor',
                        'id' => $otherCosupervisor->id,
                    ],
                    $external = [
                        'type' => 'external',
                        'name' => 'Rakesh Sharma',
                        'designation' => 'astronaut',
                        'affiliation' => 'IAF',
                        'email' => 'rakesh@gmail.com',
                        'phone' => '9469297632',
                    ],
                    $existingSupervisor = [
                        'type' => 'existing_supervisor',
                        'id' => $otherSupervisorProfile->id,
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisory Committee Updated SuccessFully!');

        $expectedAddedMembers = collect([
            AdvisoryCommitteeMember::fromExistingCosupervisors($otherCosupervisor),
            new AdvisoryCommitteeMember('external', $external),
            AdvisoryCommitteeMember::fromExistingSupervisors($otherSupervisorProfile),
        ])->sortBy('type');

        list($permanent, $added) = collect($scholar->fresh()->advisory_committee)
                ->partition(function ($item) {
                    return in_array($item->type, ['supervisor', 'cosupervisor']);
                })->map->values();

        $this->assertEquals($expectedAddedMembers, $added->sortBy('type'));
    }

    /** @test */
    public function scholar_advisory_committee_can_be_replaced_by_their_supervisor()
    {
        $this->signIn($faculty = create(User::class, 1, ['category' => UserCategory::FACULTY_TEACHER]));

        $supervisor = $faculty->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisor->id,
        ]);

        $beforeReplaceAdvisoryCommittee = $scholar->advisory_committee;

        $this->assertEquals(count($scholar->old_advisory_committees), 0);

        $otherSupervisorProfile = create(SupervisorProfile::class);
        $otherCosupervisor = create(Cosupervisor::class);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisory_committee.replace', [
                'scholar' => $scholar,
            ]), [
                'committee' => [
                    $existingCosupervisor = [
                        'type' => 'existing_cosupervisor',
                        'id' => $otherCosupervisor->id,
                    ],
                    $external = [
                        'type' => 'external',
                        'name' => 'Rakesh Sharma',
                        'designation' => 'astronaut',
                        'affiliation' => 'IAF',
                        'email' => 'rakesh@gmail.com',
                        'phone' => '9469297632',
                    ],
                    $existingSupervisor = [
                        'type' => 'existing_supervisor',
                        'id' => $otherSupervisorProfile->id,
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisory Committee Replaced SuccessFully!');

        $expectedAddedMembers = collect([
            AdvisoryCommitteeMember::fromExistingCosupervisors($otherCosupervisor),
            new AdvisoryCommitteeMember('external', $external),
            AdvisoryCommitteeMember::fromExistingSupervisors($otherSupervisorProfile),
        ])->sortBy('type');

        list($permanent, $added) = collect($scholar->fresh()->advisory_committee)
            ->partition(function ($item) {
                return in_array($item->type, ['supervisor', 'cosupervisor']);
            })->map->values();

        $this->assertEquals($expectedAddedMembers, $added->sortBy('type'));

        $expectOldCommittee = [
            'committee' => $beforeReplaceAdvisoryCommittee,
            'from_date' => today(),
            'to_date' => Carbon::parse($scholar->created_at->format('d F Y')),
        ];

        $this->assertCount(1, $scholar->fresh()->old_advisory_committees);

        $this->assertEquals(
            $expectOldCommittee,
            $scholar->fresh()->old_advisory_committees[0]
        );
    }
}
