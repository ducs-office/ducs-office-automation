<?php

namespace Tests\Feature;

use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class EditPresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentation_can_be_edited()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);
        $presentation = create(Presentation::class, 1, ['publication_id' => $publication->id, 'scholar_id' => $scholar->id]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.presentations.edit', [$scholar, $presentation]))
            ->assertSuccessful()
            ->assertViewIs('presentations.edit')
            ->assertViewHasAll(['presentation', 'eventTypes']);
    }
}
