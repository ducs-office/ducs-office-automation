<?php

namespace Tests\Feature;

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateCoAuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function co_author_of_publication_can_be_created_atomically()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->assertCount(0, $publication->coAuthors);

        $coAuthor = [
            'is_supervisor' => null,
            'is_cosupervisor' => null,
            'name' => 'John',
            'noc' => null,
        ];

        $this->withoutExceptionHandling()
            ->post(route('publications.co_authors.store', [$publication]), $coAuthor)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-Author added successfully!');

        $this->assertEquals(1, CoAuthor::count());

        $publication->refresh();

        $this->assertCount(1, $publication->coAuthors);
        $this->assertEquals(0, $publication->coAuthors->first()->type);
        $this->assertEquals($coAuthor['name'], $publication->coAuthors->first()->name);
    }
}
