<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use App\Types\PublicationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdatePublicationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $noc1;
    protected $noc2;
    protected $document;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $this->noc1 = UploadedFile::fake()
            ->create('noc1.pdf', 20, 'application/pdf');

        $this->noc2 = UploadedFile::fake()
            ->create('noc2.pdf', 20, 'application/pdf');

        $this->document = UploadedFile::fake()
            ->create('doc.pdf', 20, 'application/pdf');
    }

    /** @test */
    public function journal_publication_of_scholar_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.update', $journal), [
                'type' => PublicationType::JOURNAL,
                'number' => $number = 123,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
                'document' => $document = $this->document,
                'paper_link' => $link = 'http://somerandom.journal',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication updated successfully!');

        $freshJournal = $scholar->journals->first()->fresh();

        $this->assertEquals($number, $freshJournal->number);
        $this->assertEquals($link, $freshJournal->paper_link);

        $this->assertEquals(
            $document->hashName('publications'),
            $freshJournal->document_path
        );

        $this->assertCount(2, $freshJournal->coAuthors);

        $this->assertEquals(
            $coAuthors[0]['name'],
            $freshJournal->coAuthors->first()->name
        );

        $this->assertEquals(
            $coAuthors[0]['noc']->hashName('publications/co_authors_noc'),
            $freshJournal->coAuthors->first()->noc_path
        );
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_updated()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'author_type' => User::class,
            'author_id' => $supervisor->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.update', $journal), [
                'type' => PublicationType::JOURNAL,
                'number' => $number = 987,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
                'document' => $document = $this->document,
                'paper_link' => $link = 'http://somerandom.journal',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication updated successfully!');

        $freshJournal = $supervisor->journals->first()->fresh();

        $this->assertEquals($number, $freshJournal->number);
        $this->assertEquals($link, $freshJournal->paper_link);

        $this->assertEquals(
            $document->hashName('publications'),
            $freshJournal->document_path
        );

        $this->assertCount(2, $freshJournal->coAuthors);
        $this->assertEquals(
            $coAuthors[0]['name'],
            $freshJournal->coAuthors->first()->name
        );

        $this->assertEquals(
            $coAuthors[0]['noc']->hashName('publications/co_authors_noc'),
            $freshJournal->coAuthors->first()->noc_path
        );
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => PublicationType::CONFERENCE,
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.update', $conference), [
                'type' => PublicationType::CONFERENCE,
                'city' => $city = 'Agra',
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
                'document' => $document = $this->document,
                'paper_link' => $link = 'http://somerandom.journal',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication updated successfully!');

        $freshConference = $scholar->conferences->first()->fresh();

        $this->assertEquals($city, $freshConference->city);
        $this->assertEquals($link, $freshConference->paper_link);

        $this->assertEquals(
            $document->hashName('publications'),
            $freshConference->document_path
        );

        $this->assertCount(2, $freshConference->coAuthors);

        $this->assertEquals(
            $coAuthors[0]['name'],
            $freshConference->coAuthors->first()->name
        );

        $this->assertEquals(
            $coAuthors[0]['noc']->hashName('publications/co_authors_noc'),
            $freshConference->coAuthors->first()->noc_path
        );
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_updated()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $conference = create(Publication::class, 1, [
            'type' => PublicationType::CONFERENCE,
            'author_type' => User::class,
            'author_id' => $supervisor->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.update', $conference), [
                'type' => PublicationType::CONFERENCE,
                'city' => $city = 'Agra',
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
                'document' => $document = $this->document,
                'paper_link' => $link = 'http://somerandom.journal',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication updated successfully!');

        $freshConference = $supervisor->conferences()->first();

        $this->assertEquals($city, $freshConference->city);
        $this->assertEquals($link, $freshConference->paper_link);

        $this->assertEquals(
            $document->hashName('publications'),
            $freshConference->document_path
        );

        $this->assertCount(2, $freshConference->coAuthors);
        $this->assertEquals(
            $coAuthors[0]['name'],
            $freshConference->coAuthors->first()->name
        );

        $this->assertEquals(
            $coAuthors[0]['noc']->hashName('publications/co_authors_noc'),
            $freshConference->coAuthors->first()->noc_path
        );
    }

    /** @test */
    public function exisitng_document_is_deleted_if_a_new_one_is_uploaded_on_updating_a_scholar_publication()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.update', $publication), [
                'type' => $publication->type,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'document' => $document = $this->document,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication updated successfully!');

        $freshpublication = $scholar->publications()->first();

        Storage::assertMissing($publication->document_path);
        $this->assertEquals(
            $document->hashName('publications/'),
            $freshpublication->document_path
        );
    }

    /** @test */
    public function exisitng_document_is_deleted_if_a_new_one_is_uploaded_on_updating_a_supervisor_publication()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signInScholar($supervisor);

        $publication = create(Publication::class, 1, [
            'author_type' => User::class,
            'author_id' => $supervisor->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.update', $publication), [
                'type' => $publication->type,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'document' => $document = $this->document,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication updated successfully!');

        $freshpublication = $supervisor->publications()->first();

        Storage::assertMissing($publication->document_path);
        $this->assertEquals(
            $document->hashName('publications/'),
            $freshpublication->document_path
        );
    }

    /** @test */
    public function city_and_country_can_not_be_updated_to_null_if_publication_type_is_conference()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $conference = create(Publication::class, 1, [
            'author_type' => User::class,
            'author_id' => $supervisor->id,
            'type' => PublicationType::CONFERENCE,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('publications.update', $conference), [
                    'type' => PublicationType::CONFERENCE,
                    'date' => [
                        'month' => 'January',
                        'year' => 2020,
                    ],
                    'city' => '',
                    'country' => '',
                ]);

            $this->fail('city and country can not be null in case of a conference');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('city', $e->errors());
            $this->assertArrayHasKey('country', $e->errors());
        }

        $this->assertEquals($conference->city, $conference->fresh()->city);
        $this->assertEquals($conference->country, $conference->fresh()->country);
    }

    /** @test */
    public function publisher_can_not_be_updated_to_null_if_publication_type_is_journal()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $journal = create(Publication::class, 1, [
            'author_type' => User::class,
            'author_id' => $supervisor->id,
            'type' => PublicationType::JOURNAL,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('publications.update', $journal), [
                    'type' => PublicationType::JOURNAL,
                    'date' => [
                        'month' => 'January',
                        'year' => 2020,
                    ],
                    'publisher' => '',
                ]);

            $this->fail('publication can not be updated to null in case of a journal');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('publisher', $e->errors());
        }

        $this->assertEquals($journal->publisher, $journal->fresh()->publisher);
    }

    /** @test */
    public function date_month_and_date_year_can_not_be_updated_to_null()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $journal = create(Publication::class, 1, [
            'author_type' => User::class,
            'author_id' => $supervisor->id,
            'type' => PublicationType::JOURNAL,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('publications.update', $journal), [
                    'type' => PublicationType::JOURNAL,
                    'date' => [
                        'month' => '',
                        'year' => '',
                    ],
                ]);

            $this->fail('date_month and date_year can not be updated to null in case of a journal');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
        }

        $this->assertEquals($journal->date, $journal->fresh()->date);
    }
}
