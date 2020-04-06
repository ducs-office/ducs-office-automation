<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateScholarJournalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_can_be_created()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
        ->get(route('scholars.profile.journal.create'))
        ->assertSuccessful()
        ->assertViewIs('scholars.publications.journals.create')
        ->assertViewHas('indexedIn');
    }

    /** @test */
    public function conference_publication_can_be_created()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
        ->get(route('scholars.profile.conference.create'))
        ->assertSuccessful()
        ->assertViewIs('scholars.publications.conferences.create')
        ->assertViewHas('indexedIn');
    }
}
