<?php

namespace Tests\Feature;

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateCoAuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function co_author_of_publication_can_be_created()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->assertCount(0, $publication->coAuthors);

        $coAuthor = [
            'name' => 'John',
            'noc' => $noc = UploadedFile::fake()->create('noc.pdf', 20),
        ];

        $this->withoutExceptionHandling()
            ->post(route('publications.co-authors.store', [$publication]), $coAuthor)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-Author added successfully!');

        $this->assertEquals(1, CoAuthor::count());

        $publication->refresh();

        $this->assertCount(1, $publication->coAuthors);
        $this->assertEquals($coAuthor['name'], $publication->coAuthors->first()->name);
        $this->assertEquals($noc->hashName('publications/co_authors_noc'), $publication->coAuthors->first()->noc_path);
    }
}
