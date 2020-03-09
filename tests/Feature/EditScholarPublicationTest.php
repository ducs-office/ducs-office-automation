<?php

namespace Tests\Feature;

use App\AcademicDetail;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditScholarPublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function publication_can_be_edited_by_a_scholar()
    {
        $this->signInScholar($Scholar = create(Scholar::class));

        $publication = create(AcademicDetail::class, 1, [
            'type' => 'publication',
            'scholar_id' => $Scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.profile.publication.edit', $publication))
            ->assertSuccessful()
            ->assertViewIs('scholars.publications.edit')
            ->assertViewHasAll(['publication', 'indexedIn']);
    }
}
