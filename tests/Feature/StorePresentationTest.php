<?php

namespace Tests\Feature;

use App\Presentation;
use App\Publication;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StorePresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentation_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $publication = create(Publication::class, 1, ['scholar_id' => $scholar->id]);

        $presentation = [
            'publication_id' => $publication->id,
            'city' => $city = 'Agra',
            'country' => $country = 'India',
            'date' => $date = '2019-09-01',
            'scopus_indexed' => $scopusIndexed = true,
            'venue' => $venue = 'C',
        ];

        $this->withoutExceptionHandling()
            ->post(route('scholars.profile.presentation.store'), $presentation)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Presentation created successfully!');

        $this->assertCount(1, $publication->fresh()->presentations);
        $this->assertEquals($city, $publication->fresh()->presentations()->first()->city);
        $this->assertEquals($country, $publication->fresh()->presentations()->first()->country);
        $this->assertEquals($date, $publication->fresh()->presentations()->first()->date->format('Y-m-d'));
        $this->assertEquals($scopusIndexed, $publication->fresh()->presentations()->first()->scopus_indexed);
        $this->assertEquals($venue, $publication->fresh()->presentations()->first()->venue);
        $this->assertEquals($publication->id, $publication->fresh()->presentations()->first()->publication->id);
    }
}
