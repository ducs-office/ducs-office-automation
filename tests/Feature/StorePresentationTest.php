<?php

namespace Tests\Feature;

use App\Presentation;
use App\Publication;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
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
            'event_type' => $eventType = 'C',
            'event_name' => $eventName = 'Scholar\'s conference',
        ];

        $this->withoutExceptionHandling()
            ->post(route('scholars.profile.presentation.store'), $presentation)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Presentation created successfully!');

        $this->assertCount(1, $publication->fresh()->presentations);
        $this->assertEquals($city, $publication->fresh()->presentations()->first()->city);
        $this->assertEquals($country, $publication->fresh()->presentations()->first()->country);
        $this->assertEquals($date, $publication->fresh()->presentations()->first()->date->format('Y-m-d'));
        $this->assertEquals($eventType, $publication->fresh()->presentations()->first()->event_type);
        $this->assertEquals($eventName, $publication->fresh()->presentations()->first()->event_name);
        $this->assertEquals($publication->id, $publication->fresh()->presentations()->first()->publication->id);
    }
}
