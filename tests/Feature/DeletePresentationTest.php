<?php

namespace Tests\Feature;

use App\Presentation;
use App\Publication;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class DeletePresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentation_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = create(Publication::class, 1, [
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);
        $presentation = create(Presentation::class, 1, ['publication_id' => $publication->id]);

        $this->assertCount(1, $publication->fresh()->presentations);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.profile.presentation.destroy', $presentation))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Presentation deleted successfully');

        $this->assertCount(0, $publication->fresh()->presentations);
    }

    /** @test */
    public function scholar_can_not_delete_other_scholar_presentation()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $otherScholar = create(Scholar::class);

        $publication = create(Publication::class, 1, [
            'main_author_type' => Scholar::class,
            'main_author_id' => $otherScholar->id,
        ]);

        $presentation = create(Presentation::class, 1, ['publication_id' => $publication->id]);

        $this->assertCount(1, $publication->fresh()->presentations);
        $this->assertTrue($presentation->is($publication->presentations()->first()));

        try {
            $this->withoutExceptionHandling()
                ->delete(route('scholars.profile.presentation.destroy', $presentation));
        } catch (HttpException $e) {
            $this->assertEquals(new HttpException(403, 'You are not authorized to delete this presentation'), $e);
        }

        $this->assertCount(1, $publication->fresh()->presentations);
        $this->assertTrue($presentation->is($publication->presentations()->first()));
    }
}
