<?php

namespace Tests\Feature;

use App\Publication;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateScholarPublicationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function journal_publication_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'number' => 123,
            'publisher' => 'O Reilly',
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.publication.journal.update', $journal), [
                'number' => $number = 987,
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication updated successfully!');

        $this->assertEquals($number, $scholar->journals->first()->number);
    }

    /** @test */
    public function conference_publication_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'scholar_id' => $scholar->id,
            'city' => 'Delhi',
            'country' => 'India',
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.publication.conference.update', $conference), [
                'city' => $city = 'Agra',
                'date' => [
                    'month' => 'January',
                    'year' => 2020,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication updated successfully!');

        $this->assertEquals($city, $scholar->conferences->first()->city);
    }
}
