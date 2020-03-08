<?php

namespace Tests\Feature;

use App\AcademicDetail;
use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateScholarAcademicDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function publication_can_be_updated()
    {
        $this->signInScholar($Scholar = create(Scholar::class));

        $publication = create(AcademicDetail::class, 1, [
            'type' => 'publication',
            'scholar_id' => $Scholar->id,
            'volume' => $volume = '3',
        ]);
        // dd($publication);
        $this->assertEquals($volume, $Scholar->fresh()->publications->first()->volume);
        try {
            $this->withoutExceptionHandling()
            ->patch(route('scholars.profile.publication.update', $publication), ['volume' => $newVolume = 4])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication updated successfully!');
        } catch (ValidationException $e) {
            dd($e->errors());
        }

        $this->assertEquals($newVolume, $Scholar->fresh()->publications->first()->volume);
    }

    /** @test */
    public function presentation_can_be_updated()
    {
        $this->signInScholar($Scholar = create(Scholar::class));

        $presentation = create(AcademicDetail::class, 1, [
            'type' => 'presentation',
            'scholar_id' => $Scholar->id,
            'volume' => $volume = '3',
        ]);

        $this->assertEquals($volume, $Scholar->fresh()->presentations->first()->volume);

        $this->withoutExceptionHandling()
        ->patch(route('scholars.profile.presentation.update', $presentation), ['volume' => $newVolume = 4])
        ->assertRedirect()
        ->assertSessionHasFlash('success', 'Presentation updated successfully!');

        $this->assertEquals($newVolume, $Scholar->fresh()->presentations->first()->volume);
    }
}
