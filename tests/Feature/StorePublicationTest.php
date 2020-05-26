<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StorePublicationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $noc1;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();
        $this->noc1 = UploadedFile::fake()
            ->create('noc1.pdf', 20, 'application/pdf');
    }

    protected function fillPublication($overrides = [])
    {
        Storage::fake();

        $document = UploadedFile::fake()
            ->create('doc.pdf', 20, 'application/pdf');

        /** false won't work here for idPublished because it's being determined true or false
         * in prepareForValidation using `filled` function, which only checks whether the field is set or not
         * the value - false, would also mean being set
         * */
        $isPublished = $overrides['is_published'] ?? $this->faker->randomElement(['', true]);
        $type = $overrides['type'] ?? $this->faker->randomElement(PublicationType::values());

        $publicationFormDetails = [
            'is_published' => $isPublished,
            'type' => $type,
            'paper_title' => $this->faker->sentence,
            'document' => $document,
            'co_authors' => [
            ],
        ];

        if ($isPublished !== '') {
            $publicationFormDetails = $publicationFormDetails + [
                'name' => $this->faker->sentence,
                'volume' => $this->faker->numberBetween(1, 20),
                'page_numbers' => [random_int(1, 100), random_int(101, 1000)],
                'date' => [
                    'month' => $this->faker->monthName(),
                    'year' => $this->faker->year(),
                ],
                'indexed_in' => $this->faker->randomElements(CitationIndex::values(), 2),
                'number' => $type === PublicationType::JOURNAL ?
                    $this->faker->randomNumber(2) : null,
                'publisher' => $type === PublicationType::JOURNAL ?
                    $this->faker->name : null,
                'city' => $type === PublicationType::CONFERENCE ?
                    $this->faker->city : null,
                'country' => $type === PublicationType::CONFERENCE ?
                    $this->faker->country : null,
                'paper_link' => $this->faker->url,
            ];
        }

        return $this->mergeFormFields($publicationFormDetails, $overrides);
    }

    /** @test */
    public function only_paper_title_and_type_and_document_are_required_if_the_publication_is_not_yet_published()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'is_published' => '',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->publications);
    }

    /** @test */
    public function journal_publication_of_scholar_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'type' => PublicationType::JOURNAL,
            'is_published' => true,
        ]);

        try {
            $this->withoutExceptionHandling()
            ->post(route('publications.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully');
        } catch (ValidationException $e) {
            dd($e->errors());
        }

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->journals);

        $storedJournal = $scholar->journals->first();

        $this->assertEquals($journal['paper_title'], $storedJournal->paper_title);
        $this->assertEquals($journal['paper_link'], $storedJournal->paper_link);

        $this->assertEquals(
            $journal['document']->hashName('publications'),
            $storedJournal->document_path
        );
    }

    /** @test */
    public function publication_with_scholars_cosupervisor_as_co_author_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'co_authors' => [
                'is_supervisor' => '',
                'others' => [],
                'is_cosupervisor' => true,
            ],
        ]);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach([$cosupervisor->id]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('publications.store'), $journal)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Publication added successfully');
        } catch (ValidationException $e) {
            dd($e->errors());
        }

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->publications);

        $storedPublication = $scholar->publications->first();

        $this->assertCount(1, $storedPublication->coAuthors);

        $this->assertEquals(
            $scholar->currentCosupervisor->id,
            $storedPublication->coAuthors->first()->id
        );

        $this->assertEquals(2, $storedPublication->coAuthors->first()->type);
    }

    /** @test */
    public function publication_with_others_as_co_author_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'co_authors' => [
                'others' => $others = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                ],
            ],
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->publications);

        $storedPublication = $scholar->publications->first();

        $this->assertCount(1, $storedPublication->coAuthors);

        $this->assertEquals(
            $others[0]['name'],
            $storedPublication->coAuthors->first()->name
        );

        $this->assertEquals(
            $others[0]['noc']->hashName('publications/co_authors_noc'),
            $storedPublication->coAuthors->first()->noc_path
        );

        $this->assertEquals(0, $storedPublication->coAuthors->first()->type);
    }

    /** @test */
    public function publication_with_others_as_co_author_can_be_stored_without_noc()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'co_authors' => [
                'others' => $others = [
                    ['name' => 'John Doe', 'noc' => ''],
                ],
            ],
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->publications);

        $storedPublication = $scholar->publications->first();

        $this->assertCount(1, $storedPublication->coAuthors);

        $this->assertEquals(
            $others[0]['name'],
            $storedPublication->coAuthors->first()->name
        );

        $this->assertEquals(0, $storedPublication->coAuthors->first()->type);
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_stored()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $journal = $this->fillPublication([
            'type' => PublicationType::JOURNAL,
            'is_published' => true,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully');

        $this->assertCount(1, $supervisor->refresh()->journals);
        $storedJournal = $supervisor->journals->first();

        $this->assertEquals($journal['paper_title'], $storedJournal->paper_title);
        $this->assertEquals($journal['paper_link'], $storedJournal->paper_link);

        $this->assertEquals(
            $journal['document']->hashName('publications'),
            $storedJournal->document_path
        );
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = $this->fillPublication([
            'type' => PublicationType::CONFERENCE,
            'is_published' => true,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.store'), $conference)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->fresh()->conferences);
        $storedConference = $scholar->conferences->first();

        $this->assertEquals($conference['paper_title'], $storedConference->paper_title);
        $this->assertEquals($conference['paper_link'], $storedConference->paper_link);

        $this->assertEquals(
            $conference['document']->hashName('publications'),
            $storedConference->document_path
        );
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_stored()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);
        $conference = $this->fillPublication([
            'type' => PublicationType::CONFERENCE,
            'is_published' => true,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.store'), $conference)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully');

        $this->assertCount(1, $supervisor->fresh()->conferences);
        $storedConference = $supervisor->conferences->first();

        $this->assertEquals($conference['paper_title'], $storedConference->paper_title);
        $this->assertEquals($conference['paper_link'], $storedConference->paper_link);

        $this->assertEquals(
            $conference['document']->hashName('publications'),
            $storedConference->document_path
        );
    }

    /** @test */
    public function city_and_country_are_required_if_publication_type_is_conference_and_it_has_been_published()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $conference = $this->fillPublication([
            'type' => PublicationType::CONFERENCE,
            'is_published' => true,
            'city' => '',
            'country' => '',
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('publications.store'), $conference);

            $this->fail('city and country can not be null in case of a conference');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('city', $e->errors());
            $this->assertArrayHasKey('country', $e->errors());
        }

        $this->assertCount(0, $supervisor->fresh()->conferences);
    }

    /** @test */
    public function publisher_is_required_if_publication_type_is_journal_and_it_has_been_published()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);
        $journal = $this->fillPublication([
            'type' => PublicationType::JOURNAL,
            'publisher' => '',
            'is_published' => true,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('publications.store'), $journal);

            $this->fail('publication be null in case of a journal');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('publisher', $e->errors());
        }

        $this->assertCount(0, $supervisor->fresh()->journals);
    }

    /** @test */
    public function date_month_and_date_year_are_required_if_the_publication_has_been_published()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn($supervisor);

        $publication = $this->fillPublication([
            'type' => PublicationType::JOURNAL,
            'is_published' => true,
            'date' => [
                'month' => '',
                'year' => '',
            ],
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('publications.store'), $publication);

            $this->fail('date_month and date_year can not be null.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
        }

        $this->assertCount(0, $supervisor->fresh()->journals);
    }

    /** @test */
    public function coauthor_of_scholar_publication_can_be_supervisor()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();

        $this->signInScholar($scholar = create(Scholar::class));

        $scholar->supervisors()->attach($supervisor->id);

        $publication = $this->fillPublication([
            'co_authors' => [
                'is_supervisor' => true,
            ],
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.store'), $publication);

        $this->assertEquals(1, $scholar->publications->count());

        $storedPublicationCoAuthor = $scholar->publications->first()->coAuthors->first();

        $this->assertEquals($scholar->currentSupervisor->id, $storedPublicationCoAuthor->user_id);
        $this->assertEquals(1, $storedPublicationCoAuthor->type);
    }
}
