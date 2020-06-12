<?php

namespace Tests\Feature;

use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class DeletePresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentation_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);
        $presentation = create(Presentation::class, 1, ['publication_id' => $publication->id, 'scholar_id' => $scholar->id]);

        $this->assertCount(1, $publication->fresh()->presentations);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.presentation.destroy', [$scholar, $presentation]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Presentation deleted successfully');

        $this->assertCount(0, $publication->fresh()->presentations);
    }
}
