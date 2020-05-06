<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Models\User;
use App\Types\PublicationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePublicationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function journal_publication_of_scholar_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'number' => 123,
            'publisher' => 'O Reilly',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.journal.update', $journal), [
                'number' => $number = 987,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication updated successfully!');

        $this->assertEquals($number, $scholar->fresh()->journals->first()->number);
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_updated()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => create(Teacher::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signInTeacher($supervisor);

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'number' => 123,
            'publisher' => 'O Reilly',
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.journal.update', $journal), [
                'number' => $number = 987,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication updated successfully!');

        $this->assertEquals($number, $supervisorProfile->fresh()->journals->first()->number);
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => PublicationType::CONFERENCE,
            'city' => 'Delhi',
            'country' => 'India',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.conference.update', $conference), [
                'city' => $city = 'Agra',
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication updated successfully!');

        $this->assertEquals($city, $scholar->fresh()->conferences->first()->city);
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_updated()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => User::class,
            'supervisor_id' => create(User::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);

        $conference = create(Publication::class, 1, [
            'type' => PublicationType::CONFERENCE,
            'city' => 'Delhi',
            'country' => 'India',
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.conference.update', $conference), [
                'city' => $city = 'Agra',
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication updated successfully!');

        $this->assertEquals($city, $supervisorProfile->fresh()->conferences->first()->city);
    }
}
