<?php

namespace Tests\Feature;

use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class EditScholarPresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentation_can_be_edited()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $publication = create(Publication::class, 1, ['scholar_id' => $scholar->id]);
        $presentation = create(Presentation::class, 1, ['publication_id' => $publication->id]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.profile.presentation.edit', $presentation))
            ->assertSuccessful()
            ->assertViewIs('scholars.presentations.edit')
            ->assertViewHasAll(['presentation', 'publications', 'eventTypes']);
    }

    /** @test */
    public function scholar_can_not_edited_others_presentation()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $otherScholar = create(Scholar::class);
        $publication = create(Publication::class, 1, ['scholar_id' => $otherScholar->id]);
        $presentation = create(Presentation::class, 1, ['publication_id' => $publication->id]);

        $this->assertCount(1, $publication->presentations);

        try {
            $this->withoutExceptionHandling()
                ->get(route('scholars.profile.presentation.edit', $presentation));
        } catch (HttpException $e) {
            $this->assertEquals(new HttpException(403, 'You are not authorized to edit this presentation'), $e);
        }

        $this->assertTrue($presentation->is($publication->fresh()->presentations()->first()));
    }
}
