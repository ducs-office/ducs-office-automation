<?php

namespace Tests\Feature;

use App\Models\Scholar;
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
        $this->signInScholar($scholar = create(Scholar::class));

        $this->withoutExceptionHandling()
        ->get(route('scholars.publications.create', $scholar))
        ->assertSuccessful()
        ->assertViewIs('scholar-publications.create')
        ->assertViewHas('citationIndexes');
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_created()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->get(route('users.publications.create', $supervisor))
            ->assertSuccessful()
            ->assertViewIs('user-publications.create')
            ->assertViewHas('citationIndexes');
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_created()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->withoutExceptionHandling()
        ->get(route('scholars.publications.create', $scholar))
        ->assertSuccessful()
        ->assertViewIs('scholar-publications.create')
        ->assertViewHas('citationIndexes');
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_created()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
        ->get(route('users.publications.create', $supervisor))
        ->assertSuccessful()
        ->assertViewIs('user-publications.create')
        ->assertViewHas('citationIndexes');
    }
}
