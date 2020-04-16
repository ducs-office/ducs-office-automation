<?php

namespace Tests\Feature;

use App\Presentation;
use App\Publication;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class EditScholarPresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentation_can_be_edited()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $publication = create(Publication::class, 1, [
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);
        $presentation = create(Presentation::class, 1, ['publication_id' => $publication->id]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.profile.presentation.edit', $presentation))
            ->assertSuccessful()
            ->assertViewIs('scholars.presentations.edit')
            ->assertViewHasAll(['presentation', 'publications', 'eventTypes']);
    }
}
