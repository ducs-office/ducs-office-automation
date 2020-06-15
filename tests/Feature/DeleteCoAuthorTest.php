<?php

namespace Tests\Feature;

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteCoAuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function co_author_can_be_deleted()
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
            ->delete(route('publications.co-authors.destroy', [
                'publication' => $publication->id,
                'coAuthor' => $coAuthor,
            ]));

        Storage::assertMissing($coAuthor->noc_path);
        $this->assertEquals(0, CoAuthor::count());
    }
}
