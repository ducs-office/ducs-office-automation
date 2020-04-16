<?php

namespace Tests\Feature;

use App\Publication;
use App\Scholar;
use App\SupervisorProfile;
use App\Teacher;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditlPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_of_scholar_can_be_edited()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'number' => 1234,
            'publisher' => 'O Rielly',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.journal.edit', $journal))
            ->assertSuccessful()
            ->assertViewIs('publications.journals.edit')
            ->assertViewHasAll(['journal', 'indexedIn']);
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_edited()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => User::class,
            'supervisor_id' => create(User::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'number' => 1234,
            'publisher' => 'O Rielly',
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.journal.edit', $journal))
            ->assertSuccessful()
            ->assertViewIs('publications.journals.edit')
            ->assertViewHasAll(['journal', 'indexedIn']);
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_edited()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'city' => 'Delhi',
            'country' => 'India',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.conference.edit', $conference))
            ->assertSuccessful()
            ->assertViewIs('publications.conferences.edit')
            ->assertViewHasAll(['conference', 'indexedIn']);
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_edited()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => create(Teacher::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signInTeacher($supervisor);

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'city' => 'Delhi',
            'country' => 'India',
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.conference.edit', $conference))
            ->assertSuccessful()
            ->assertViewIs('publications.conferences.edit')
            ->assertViewHasAll(['conference', 'indexedIn']);
    }
}
