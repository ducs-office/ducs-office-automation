<?php

namespace Tests\Feature;

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditCoAuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function co_author_of_type_others_can_be_edited()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $noc = UploadedFile::fake()->create('noc.pdf', 10, 'application/pdf');

        $coAuthor = factory(CoAuthor::class)->states('others')->create([
            'publication_id' => $publication->id,
        ]);

        $this->withoutExceptionHandling()
                ->patch(
                    route('publications.co_authors.update', [
                        'publication' => $coAuthor->publication_id,
                        'coAuthor' => $coAuthor,
                    ]),
                    [
                        'name' => $name = 'John Doe',
                        'noc' => $noc,
                    ]
                )
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-Author updated successfully!');

        $this->assertEquals($name, $coAuthor->fresh()->name);
        $this->assertEquals(
            $noc->hashName('publications/co_authors_noc'),
            $coAuthor->fresh()->noc_path
        );
    }
}
