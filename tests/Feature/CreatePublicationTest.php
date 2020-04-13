<?php

namespace Tests\Feature;

use App\Models\SupervisorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreatePublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_of_scholar_can_be_created()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
        ->get(route('publications.journal.create'))
        ->assertSuccessful()
        ->assertViewIs('publications.journals.create')
        ->assertViewHas('citationIndexes');
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_created()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => User::class,
            'supervisor_id' => create(User::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
        ->get(route('publications.journal.create'))
        ->assertSuccessful()
        ->assertViewIs('publications.journals.create')
        ->assertViewHas('citationIndexes');
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_created()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
        ->get(route('publications.conference.create'))
        ->assertSuccessful()
        ->assertViewIs('publications.conferences.create')
        ->assertViewHas('citationIndexes');
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_created()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => User::class,
            'supervisor_id' => create(User::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
        ->get(route('publications.conference.create'))
        ->assertSuccessful()
        ->assertViewIs('publications.conferences.create')
        ->assertViewHas('citationIndexes');
    }
}
