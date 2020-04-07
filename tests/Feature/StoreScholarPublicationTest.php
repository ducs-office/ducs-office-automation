<?php

namespace Tests\Feature;

use App\Publication;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreScholarJournalPresentationTest extends TestCase
{
    use RefreshDatabase;

    protected function fillPublication($overrides = [])
    {
        return $this->mergeFormFields([
            'type' => null,
            'name' => 'India CS Journal',
            'authors' => ['JOhn Doe', 'Sally Brooke'],
            'paper_title' => 'Lorem ipsum dolor sit amet consectetur adipisicing',
            'date' => '2020-02-09',
            'volume' => '1',
            'page_numbers' => ['23', '80'],
            'indexed_in' => ['Scopus', 'SCI'],
            'number' => null,
            'publisher' => null,
            'city' => null,
            'country' => null,
        ], $overrides);
    }

    /** @test */
    public function journal_publication_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'type' => 'journal',
            'number' => 123,
            'publisher' => 'O Reilly',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('scholars.profile.publication.journal.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->fresh()->journals);
        $this->assertEquals($journal['paper_title'], $scholar->journals->first()->paper_title);
    }

    /** @test */
    public function conference_publication_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = $this->fillPublication([
            'type' => 'conference',
            'city' => 'Delhi',
            'country' => 'India',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('scholars.profile.publication.conference.store'), $conference)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->fresh()->conferences);
        $this->assertEquals($conference['paper_title'], $scholar->conferences->first()->paper_title);
    }
}
