<?php

namespace Tests\Unit;

use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function array_of_authors_is_stored_separated_by_pipes()
    {
        $publication = new Publication();

        $authors = ['Pagak G.', 'Auilers K.'];

        $publication->authors = $authors;

        $this->assertEquals(implode('|', $authors), $publication->getAttributes()['authors']);
    }

    /** @test */
    public function authors_separated_by_pipe_returned_as_array()
    {
        $publication = new Publication();

        $authors = ['Pagak G.', 'Auilers K.'];

        $publication->authors = implode('|', $authors);

        $this->assertSame($authors, $publication->authors);
    }

    /** @test */
    public function array_of_indexed_in_is_stored_separated_by_pipe()
    {
        $publication = new Publication();

        $indexed_in = ['SCI', 'SCIE'];

        $publication->indexed_in = $indexed_in;

        $this->assertEquals(implode('|', $indexed_in), $publication->getAttributes()['indexed_in']);
    }

    /** @test */
    public function indexed_in_separated_by_pipe_returned_as_array()
    {
        $publication = new Publication();

        $indexed_in = ['SCI', 'SCIE'];

        $publication->indexed_in = implode('|', $indexed_in);

        $this->assertSame($indexed_in, $publication->indexed_in);
    }

    /** @test */
    public function array_of_page_numbers_is_stored_seperated_by_hyphen()
    {
        $publication = new Publication();

        $page_numbers = ['23', '49'];

        $publication->page_numbers = $page_numbers;

        $this->assertEquals(implode('-', $page_numbers), $publication->getAttributes()['page_numbers']);
    }

    public function page_numbers_separated_by_hyphen_returned_as_array()
    {
        $publication = new Publication();

        $page_numbers = ['23', '49'];

        $publication->page_numbers = implode('-', $page_numbers);

        $this->assertSame($page_numbers, $publication->page_numbers);
    }

    /** @test */
    public function publication_has_many_presentations()
    {
        $publication = create(Publication::class);
        $this->assertInstanceOf(HasMany::class, $publication->presentations());
        $this->assertCount(0, $publication->presentations);

        $presentation = create(Presentation::class, 1, [
            'publication_id' => $publication->id,
        ]);

        $this->assertCount(1, $publication->fresh()->presentations);
        $this->assertTrue($presentation->is($publication->presentations()->first()));
    }

    /** @test */
    public function publication_belongs_to_main_author_via_morphTo()
    {
        $scholar = create(Scholar::class);

        $publication = create(Publication::class, 1, [
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $publication->mainAuthor());
        $this->assertTrue($publication->mainAuthor->is($scholar));

        $supervisorProfile = create(SupervisorProfile::class);

        $publication = create(Publication::class, 1, [
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $publication->mainAuthor());
        $this->assertTrue($publication->mainAuthor->is($supervisorProfile));
    }
}
