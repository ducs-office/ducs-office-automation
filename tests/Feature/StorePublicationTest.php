<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorePublicationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function fillPublication($overrides = [])
    {
        Storage::fake();
        $noc1 = UploadedFile::fake()
            ->create('noc1.pdf', 100, 'application/pdf');

        $noc2 = UploadedFile::fake()
        ->create('noc2.pdf', 100, 'application/pdf');

        return $this->mergeFormFields([
            'paper_title' => $this->faker->sentence,
            'name' => $this->faker->sentence,
            'volume' => $this->faker->numberBetween(1, 20),
            'page_numbers' => [random_int(1, 100), random_int(101, 1000)],
            'date' => [
                'month' => $this->faker->monthName(),
                'year' => $this->faker->year(),
            ],
            'indexed_in' => $this->faker->randomElements(CitationIndex::values(), 2),
            'number' => null,
            'publisher' => null,
            'city' => null,
            'country' => null,
            'co_authors' => [
                ['name' => 'John Doe', 'noc' => $noc1],
                ['name' => 'Sally Burgman', 'noc' => $noc2],
            ],
        ], $overrides);
    }

    /** @test */
    public function journal_publication_of_scholar_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'type' => PublicationType::JOURNAL,
            'number' => 123,
            'publisher' => 'O Reilly',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.journal.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->journals);
        $this->assertEquals($journal['paper_title'], $scholar->journals->first()->paper_title);

        $storedJournal = $scholar->journals->first();

        $this->assertCount(2, $storedJournal->coAuthors);
        $this->assertEquals(
            $journal['co_authors'][0]['name'],
            $storedJournal->coAuthors->first()->name
        );
        $this->assertEquals(
            $journal['co_authors'][0]['noc']->hashName('publications/co_authors_noc'),
            $storedJournal->coAuthors->first()->noc_path
        );
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_stored()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => create(Teacher::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signInTeacher($supervisor);

        $journal = $this->fillPublication([
            'type' => PublicationType::JOURNAL,
            'number' => 123,
            'publisher' => 'O Reilly',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.journal.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $supervisor->fresh()->supervisorProfile->journals);
        $this->assertEquals($journal['paper_title'], $supervisor->supervisorProfile->journals->first()->paper_title);

        $storedJournal = $supervisor->supervisorProfile->journals->first();

        $this->assertCount(2, $storedJournal->coAuthors);
        $this->assertEquals(
            $journal['co_authors'][0]['name'],
            $storedJournal->coAuthors->first()->name
        );
        $this->assertEquals(
            $journal['co_authors'][0]['noc']->hashName('publications/co_authors_noc'),
            $storedJournal->coAuthors->first()->noc_path
        );
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = $this->fillPublication([
            'type' => PublicationType::CONFERENCE,
            'city' => 'Delhi',
            'country' => 'India',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.conference.store'), $conference)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->fresh()->conferences);
        $this->assertEquals($conference['paper_title'], $scholar->conferences->first()->paper_title);

        $storedConference = $scholar->conferences->first();

        $this->assertCount(2, $storedConference->coAuthors);
        $this->assertEquals(
            $conference['co_authors'][0]['name'],
            $storedConference->coAuthors->first()->name
        );
        $this->assertEquals(
            $conference['co_authors'][0]['noc']->hashName('publications/co_authors_noc'),
            $storedConference->coAuthors->first()->noc_path
        );
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_stored()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => create(Teacher::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signInTeacher($supervisor);
        $conference = $this->fillPublication([
            'type' => PublicationType::CONFERENCE,
            'city' => 'Delhi',
            'country' => 'India',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.conference.store'), $conference)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $supervisor->fresh()->supervisorProfile->conferences);
        $this->assertEquals($conference['paper_title'], $supervisor->supervisorProfile->conferences->first()->paper_title);

        $storedConference = $supervisor->supervisorProfile->conferences->first();

        $this->assertCount(2, $storedConference->coAuthors);
        $this->assertEquals(
            $conference['co_authors'][0]['name'],
            $storedConference->coAuthors->first()->name
        );
        $this->assertEquals(
            $conference['co_authors'][0]['noc']->hashName('publications/co_authors_noc'),
            $storedConference->coAuthors->first()->noc_path
        );
    }
}
