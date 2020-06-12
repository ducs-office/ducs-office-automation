<?php

namespace Tests\Feature;

use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentaion_can_be_updated()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $presentation = create(Presentation::class, 1, [
            'scholar_id' => $scholar->id,
            'publication_id' => $publication->id,
            'country' => $country = 'Australia',
        ]);

        $this->assertCount(1, $publication->fresh()->presentations);
        $this->assertTrue($presentation->is($publication->fresh()->presentations()->first()));

        $newCountry = 'India';

        $this->withoutExceptionHandling()
        ->patch(route('scholars.presentations.update', [$scholar, $presentation]), ['country' => $newCountry])
        ->assertRedirect()
        ->assertSessionHasFlash('success', 'Presentation updated successfully!');

        $this->assertEquals($newCountry, $presentation->fresh()->country);
    }
}
