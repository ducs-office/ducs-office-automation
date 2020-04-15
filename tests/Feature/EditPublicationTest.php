<?php

namespace Tests\Feature;

use App\Publication;
use App\SupervisorProfile;
use App\Teacher;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditScholarlPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_of_scholar_can_be_edited()
    {
        $this->signInScholar();

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'number' => 1234,
            'publisher' => 'O Rielly',
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
        $this->signInScholar();

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'city' => 'Delhi',
            'country' => 'India',
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
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.conference.edit', $conference))
            ->assertSuccessful()
            ->assertViewIs('publications.conferences.edit')
            ->assertViewHasAll(['conference', 'indexedIn']);
    }
}
