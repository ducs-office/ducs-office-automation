<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_of_scholar_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->journals);

        $this->withoutExceptionHandling()
            ->delete(route('publications.journal.destroy', $journal))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->journals);
        $this->assertNull($journal->fresh());
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_deleted()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'main_author_type' => User::class,
            'main_author_id' => $supervisor->id,
        ]);

        $this->assertCount(1, $supervisor->fresh()->journals);

        $this->withoutExceptionHandling()
            ->delete(route('publications.journal.destroy', $journal))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication deleted successfully!');

        $this->assertCount(0, $supervisor->fresh()->journals);
        $this->assertNull($journal->fresh());
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->conferences);

        $this->withoutExceptionHandling()
            ->delete(route('publications.conference.destroy', $conference))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->conferences);
        $this->assertNull($conference->fresh());
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_deleted()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);
        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'main_author_type' => User::class,
            'main_author_id' => $supervisor->id,
        ]);

        $this->assertCount(1, $supervisor->fresh()->conferences);

        $this->withoutExceptionHandling()
            ->delete(route('publications.conference.destroy', $conference))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication deleted successfully!');

        $this->assertCount(0, $supervisor->fresh()->conferences);
        $this->assertNull($conference->fresh());
    }
}
