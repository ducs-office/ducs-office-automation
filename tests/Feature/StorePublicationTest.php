<?php

namespace Tests\Feature;

use App\Publication;
use App\Scholar;
use App\SupervisorProfile;
use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StorePublicationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function fillPublication($overrides = [])
    {
        return $this->mergeFormFields([
            'authors' => [$this->faker->name, $this->faker->name],
            'paper_title' => $this->faker->sentence,
            'name' => $this->faker->sentence,
            'volume' => $this->faker->numberBetween(1, 20),
            'page_numbers' => [random_int(1, 100), random_int(101, 1000)],
            'date' => [
                'month' => $this->faker->monthName(),
                'year' => $this->faker->year(),
            ],
            'indexed_in' => [$this->faker->randomElement(array_keys(config('options.scholars.academic_details.indexed_in')))],
            'number' => null,
            'publisher' => null,
            'city' => null,
            'country' => null,
        ], $overrides);
    }

    /** @test */
    public function journal_publication_of_scholar_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = $this->fillPublication([
            'type' => 'journal',
            'number' => 123,
            'publisher' => 'O Reilly',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('publications.journal.store'), $journal)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication added successfully');

        $this->assertCount(1, Publication::all());
        $this->assertCount(1, $scholar->fresh()->journals);
        $this->assertEquals($journal['paper_title'], $scholar->journals->first()->paper_title);
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
            'type' => 'journal',
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
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = $this->fillPublication([
            'type' => 'conference',
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
            'type' => 'conference',
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
    }
}
