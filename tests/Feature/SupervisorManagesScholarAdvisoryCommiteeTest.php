<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\User;
use App\Types\AdvisoryCommitteeMember;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupervisorManagesScholarAdvisoryCommiteeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_advisory_committee_can_be_edited_by_their_supervisor()
    {
        $this->signIn($faculty = create(User::class, 1, ['category' => 'faculty_teacher']));

        $supervisor = $faculty->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisor->id,
        ]);

        $faculty_teacher = create(User::class, 1, ['category' => 'faculty_teacher']);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisory_committee.update', [
                'scholar' => $scholar,
            ]), [
                'committee' => [
                    [
                        'type' => 'faculty_teacher',
                        'id' => $faculty_teacher->id,
                    ],
                    $external = [
                        'type' => 'external',
                        'name' => 'Rakesh Sharma',
                        'designation' => 'astronaut',
                        'affiliation' => 'IAF',
                        'email' => 'rakesh@gmail.com',
                        'phone' => '9469297632',
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisory Committee Updated SuccessFully!');

        // TODO: change assertions
        // $this->assertEquals()
        // $this->assertEquals($scholar->fresh()->advisory_committee['faculty_teacher'], $faculty_teacher->name);
        // $this->assertEquals($scholar->fresh()->advisory_committee['external'], $external);
    }

    /** @test */
    public function scholar_advisory_committee_can_be_replaced_by_their_supervisor()
    {
        $this->signIn($faculty = create(User::class, 1, ['category' => 'faculty_teacher']));

        $supervisor = $faculty->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisor->id,
        ]);

        $beforeReplaceAdvisoryCommittee = $scholar->advisory_committee;

        $this->assertEquals(count($scholar->old_advisory_committees), 0);

        $faculty_teacher = create(User::class, 1, ['category' => 'faculty_teacher']);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisory_committee.replace', [
                'scholar' => $scholar,
            ]), [
                'committee' => [
                    [
                        'type' => 'faculty_teacher',
                        'id' => $faculty_teacher->id,
                    ],
                    $external = [
                        'type' => 'external',
                        'name' => 'Rakesh Sharma',
                        'designation' => 'astronaut',
                        'affiliation' => 'IAF',
                        'email' => 'rakesh@gmail.com',
                        'phone' => '9469297632',
                    ],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisory Committee Replaced SuccessFully!');

        $this->assertContains($external, collect($scholar->fresh()->advisory_committee)->map->toArray()->toArray());

        $expectOldCommittee = collect($beforeReplaceAdvisoryCommittee)->map->toArray()
            ->put('date', now()->format('d F Y'))->toArray();

        $this->assertEquals(count($scholar->fresh()->old_advisory_committees), 1);

        $this->assertEquals(
            $expectOldCommittee,
            $scholar->fresh()->old_advisory_committees[0]
        );
    }
}
