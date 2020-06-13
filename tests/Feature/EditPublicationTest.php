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

        $publication = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'number' => 1234,
            'publisher' => 'O Rielly',
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.publications.edit', [$scholar, $publication]))
            ->assertSuccessful()
            ->assertViewIs('scholar-publications.edit')
            ->assertViewHasAll(['publication', 'citationIndexes', 'months']);
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_edited()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $publication = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'number' => 1234,
            'publisher' => 'O Rielly',
            'author_type' => User::class,
            'author_id' => $supervisor->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('users.publications.edit', [$supervisor, $publication]))
            ->assertSuccessful()
            ->assertViewIs('user-publications.edit')
            ->assertViewHasAll(['publication', 'citationIndexes', 'months']);
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_edited()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => PublicationType::CONFERENCE,
            'city' => 'Delhi',
            'country' => 'India',
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.publications.edit', [$scholar, $conference]))
            ->assertSuccessful()
            ->assertViewIs('scholar-publications.edit')
            ->assertViewHasAll(['publication', 'citationIndexes', 'months']);
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
            'author_type' => User::class,
            'author_id' => $supervisor->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('users.publications.edit', [$supervisor, $conference]))
            ->assertSuccessful()
            ->assertViewIs('user-publications.edit')
            ->assertViewHasAll(['publication', 'citationIndexes', 'months']);
    }
}
