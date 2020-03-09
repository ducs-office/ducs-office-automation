<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateScholarPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function publication_can_be_created_by_a_scholar()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
            ->get(route('scholars.profile.publication.create'))
            ->assertSuccessful()
            ->assertViewIs('scholars.publications.create')
            ->assertViewHas('indexedIn');
    }
}
