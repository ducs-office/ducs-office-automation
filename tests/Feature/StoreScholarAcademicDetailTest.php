<?php

namespace Tests\Feature;

use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreScholarAcademicDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function fillAcademicDetails($overrides = [])
    {
        return $this->mergeFormFields([
            'authors' => ['Finnman', 'Myer'],
            'title' => 'Goromov invariants for holomorphic maps on Reimann surfaces',
            'conference' => '8th Symposium on Catecholamines and Other Neurotransmitters in Stress',
            'volume' => '4',
            'publisher' => 'Apl. Phy. Lett.',
            'page_numbers' => ['23', '80'],
            'date' => '2019-07-12',
            'number' => '221109',
            'city' => 'Moscow',
            'country' => 'Russia',
            'indexed_in' => ['Scopus', 'SCI'],
        ], $overrides);
    }

    /** @test */
    public function academic_detail_of_publication_type_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $publication = $this->fillAcademicDetails();

        $this->withoutExceptionHandling()
            ->post(route('scholars.profile.publication.store'), $publication)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Publication added successfully!');

        $this->assertCount(1, $scholar->fresh()->publications);
        $this->assertEquals($publication['number'], $scholar->fresh()->publications->first()->number);
    }

    /** @test */
    public function academic_detail_of_presentation_type_can_be_stored()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $presentation = $this->fillAcademicDetails();

        $this->withoutExceptionHandling()
            ->post(route('scholars.profile.presentation.store'), $presentation)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Presentation added successfully!');

        $this->assertCount(1, $scholar->fresh()->presentations);
        $this->assertEquals($presentation['number'], $scholar->fresh()->presentations->first()->number);
    }
}
