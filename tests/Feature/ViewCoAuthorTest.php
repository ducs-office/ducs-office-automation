<?php

namespace Tests\Feature;

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewCoAuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function co_author_noc_can_viewed()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $coAuthor = create(CoAuthor::class, 1, [
            'publication_id' => $publication->id,
        ]);

        $this->assertEquals(1, CoAuthor::count());

        $this->withoutExceptionHandling()
            ->get(route('publications.co_authors.show', [$publication, $coAuthor]))
            ->assertSuccessful();
    }
}
