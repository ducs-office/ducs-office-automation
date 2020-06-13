<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_view_their_publication_document()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.publications.show', [$scholar, $publication]))
            ->assertSuccessful();
    }

    /** @test */
    public function scholar_can_not_view_other_scholar_publication_document()
    {
        $this->signInScholar();

        $otherScholar = create(Scholar::class);

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $otherScholar->id,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.publications.show', [$otherScholar, $publication]))
            ->assertForbidden();
    }

    /** @test */
    public function supervisor_can_view_the_publication_document_of_scholars_that_they_supervise()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user->id);

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.publications.show', [$scholar, $publication]))
            ->assertSuccessful();
    }

    /** @test */
    public function supervisor_can_not_view_the_publication_document_of_scholars_that_they_do_not_supervise()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.publications.show', [$scholar, $publication]))
            ->assertForbidden();
    }
}
