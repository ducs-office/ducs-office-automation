<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
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

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $this->noc1 = UploadedFile::fake()
                        ->create('noc1.pdf', 20, 'application/pdf');

        $this->noc2 = UploadedFile::fake()
                        ->create('noc2.pdf', 20, 'application/pdf');
    }

    /** @test */
    public function journal_publication_of_scholar_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.journal.update', $journal), [
                'number' => $number = 987,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication updated successfully!');

        $freshJournal = $scholar->journals->first()->fresh();

        $this->assertEquals($number, $freshJournal->number);

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
        $supervisorProfile = create(SupervisorProfile::class);
        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);

        $journal = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.journal.update', $journal), [
                'number' => $number = 987,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication updated successfully!');

        $freshJournal = $supervisorProfile->journals->first()->fresh();

        $this->assertEquals($number, $freshJournal->number);

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
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.conference.update', $conference), [
                'city' => $city = 'Agra',
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication updated successfully!');

        $freshConference = $scholar->conferences->first()->fresh();

        $this->assertEquals($city, $freshConference->city);

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
        $supervisorProfile = create(SupervisorProfile::class);
        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);

        $conference = create(Publication::class, 1, [
            'type' => PublicationType::CONFERENCE,
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('publications.conference.update', $conference), [
                'city' => $city = 'Agra',
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
                'co_authors' => $coAuthors = [
                    ['name' => 'John Doe', 'noc' => $this->noc1],
                    ['name' => 'Sally Burgman', 'noc' => $this->noc2],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication updated successfully!');

        $freshConference = $supervisorProfile->conferences->first()->fresh();

        $this->assertEquals($city, $freshConference->city);

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
}
