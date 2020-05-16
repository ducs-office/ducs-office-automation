<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use App\Types\PublicationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_of_scholar_can_be_edited()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'number' => 1234,
            'publisher' => 'O Rielly',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.journal.edit', $journal))
            ->assertSuccessful()
            ->assertViewIs('publications.journals.edit')
            ->assertViewHasAll(['journal', 'citationIndexes']);
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_edited()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'number' => 1234,
            'publisher' => 'O Rielly',
            'main_author_type' => User::class,
            'main_author_id' => $supervisor->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.journal.edit', $journal))
            ->assertSuccessful()
            ->assertViewIs('publications.journals.edit')
            ->assertViewHasAll(['journal', 'citationIndexes']);
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_edited()
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
            ->get(route('publications.conference.edit', $conference))
            ->assertSuccessful()
            ->assertViewIs('publications.conferences.edit')
            ->assertViewHasAll(['conference', 'citationIndexes']);
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_edited()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $conference = create(Publication::class, 1, [
            'type' => PublicationType::CONFERENCE,
            'city' => 'Delhi',
            'country' => 'India',
            'main_author_type' => User::class,
            'main_author_id' => $supervisor->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('publications.conference.edit', $conference))
            ->assertSuccessful()
            ->assertViewIs('publications.conferences.edit')
            ->assertViewHasAll(['conference', 'citationIndexes']);
    }
}
