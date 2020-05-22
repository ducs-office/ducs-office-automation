<?php

namespace Tests\Unit;

use App\Casts\CustomTypeArray;
use App\Models\CoAuthor;
use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\User;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use CreateCoauthorsTable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Psy\Command\DumpCommand;
use Tests\TestCase;

class PublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function indexed_in_attributes_uses_CustomTypeArray_caster_class()
    {
        $publication = new Publication();

        $this->assertArrayHasKey('indexed_in', $publication->getCasts());
        $this->assertEquals(
            CustomTypeArray::class . ':' . CitationIndex::class,
            $publication->getCasts()['indexed_in']
        );
    }

    /** @test */
    public function array_of_page_numbers_is_stored_seperated_by_hyphen()
    {
        $publication = new Publication();

        $this->assertArrayHasKey('page_numbers', $publication->getCasts());
        $this->assertEquals('array', $publication->getCasts()['page_numbers']);
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
    public function publication_belongs_to_a_scholar_or_supervisor_author_via_morphTo()
    {
        $scholar = create(Scholar::class);

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $publication->author());
        $this->assertTrue($publication->author->is($scholar));

        $supervisor = factory(User::class)->states('supervisor')->create();

        $publication = create(Publication::class, 1, [
            'author_type' => User::class,
            'author_id' => $supervisor->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $publication->author());
        $this->assertTrue($publication->author->is($supervisor));
    }

    /** @test */
    public function publications_has_journal_scope_ordered_by_descending_date()
    {
        $journals = create(Publication::class, 3, ['type' => PublicationType::JOURNAL])
                        ->sortByDesc('date');

        $this->assertCount(3, Publication::journal()->get());
        $this->assertEquals($journals->pluck('id'), Publication::journal()->get()->pluck('id'));
    }

    /** @test */
    public function publications_has_conference_scope_ordered_by_descending_date()
    {
        $conferences = create(Publication::class, 3, ['type' => PublicationType::CONFERENCE])
                        ->sortByDesc('date');

        $this->assertCount(3, Publication::conference()->get());
        $this->assertEquals($conferences->pluck('id'), Publication::conference()->get()->pluck('id'));
    }

    /** @test */
    public function publications_has_many_co_authors()
    {
        $publication = create(Publication::class);

        $this->assertInstanceOf(HasMany::class, $publication->coAuthors());
        $this->assertCount(0, $publication->coAuthors);

        $coAuthors = create(CoAuthor::class, 3, ['publication_id' => $publication->id]);

        $freshPublication = $publication->fresh();

        $this->assertCount(3, $freshPublication->coAuthors);
        $this->assertEquals($coAuthors->pluck('id'), $freshPublication->coAuthors->pluck('id'));
    }

    /** @test */
    public function isPublished_method_returns_boolean_value_for_publication_being_published_or_not()
    {
        $publication = create(Publication::class, 1, ['is_published' => false]);

        $this->assertFalse($publication->isPublished());

        $publication->update(['is_published' => true]);

        $this->assertTrue($publication->fresh()->isPublished());
    }
}
