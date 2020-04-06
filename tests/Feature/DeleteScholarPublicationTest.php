<?php

namespace Tests\Feature;

use App\Publication;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteScholarJournalPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'scholar_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->journals);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.profile.journal.destroy', $journal))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->journals);
        $this->assertNull($journal->fresh());
    }

    /** @test */
    public function conference_publication_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'scholar_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->conferences);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.profile.conference.destroy', $conference))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->conferences);
        $this->assertNull($conference->fresh());
    }
}
