<?php

namespace Tests\Feature;

use App\AcademicDetail;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteScholarAcademicDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function publication_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(AcademicDetail::class, 1, [
            'type' => 'publication',
            'scholar_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->publications);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.profile.publication.destroy', $publication))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->publications);
        $this->assertNull($publication->fresh());
    }

    /** @test */
    public function presentation_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $presentation = create(AcademicDetail::class, 1, [
            'type' => 'presentation',
            'scholar_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->presentations);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.profile.presentation.destroy', $presentation))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Presentation deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->presentations);
        $this->assertNull($presentation->fresh());
    }
}
