<?php

namespace Tests\Feature;

use App\Publication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditScholarJournalPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_can_be_edited()
    {
        $this->signInScholar();

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'number' => 1234,
            'publisher' => 'O Rielly',
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.profile.journal.edit', $journal))
            ->assertSuccessful()
            ->assertViewIs('scholars.publications.journals.edit')
            ->assertViewHasAll(['journal', 'indexedIn']);
    }

    /** @test */
    public function conference_publication_can_be_edited()
    {
        $this->signInScholar();

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'city' => 'Delhi',
            'country' => 'India',
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.profile.conference.edit', $conference))
            ->assertSuccessful()
            ->assertViewIs('scholars.publications.conferences.edit')
            ->assertViewHasAll(['conference', 'indexedIn']);
    }
}
