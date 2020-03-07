<?php

namespace Tests\Unit;

use App\Scholar;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarTest extends TestCase
{
    use RefreshDatabase;

    protected function fillAcademicDetails($overrides = [])
    {
        return $this->mergeFormFields([
            'type' => 'publication',
            'authors' => ['Finnman', 'Myer'],
            'title' => 'Goromov invariants for holomorphic maps on Reimann surfaces',
            'conference' => '8th Symposium on Catecholamines and Other Neurotransmitters in Stress',
            'volume' => '4',
            'publisher' => 'Apl. Phy. Lett.',
            'page_numbers' => ['from' => '23', 'to' => '80'],
            'date' => '2019-07-12',
            'number' => '221109',
            'venue' => ['city' => 'Moscow', 'Country' => 'Russia'],
            'indexed_in' => ['Scopus', 'SCI'],
        ], $overrides);
    }

    /** @test */
    public function scholar_has_many_academic_details()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->academicDetails());

        $scholar->academicDetails()->createMany([
            $this->fillAcademicDetails(),
            $this->fillAcademicDetails(),
            $this->fillAcademicDetails(),
        ]);

        $this->assertCount(3, $scholar->academicDetails);
    }

    /** @test */
    public function scholar_has_many_publications()
    {
        $scholar = create(Scholar::class);

        $scholar->academicDetails()->createMany([
            $this->fillAcademicDetails(['type' => 'publication']),
            $this->fillAcademicDetails(['type' => 'publication']),
            $this->fillAcademicDetails(['type' => 'presentation']),
        ]);

        $this->assertCount(2, $scholar->publications);
    }

    /** @test */
    public function scholar_has_many_presentations()
    {
        $scholar = create(Scholar::class);

        $scholar->academicDetails()->createMany([
            $this->fillAcademicDetails(['type' => 'presentation']),
            $this->fillAcademicDetails(['type' => 'presentation']),
            $this->fillAcademicDetails(['type' => 'publication']),
        ]);

        $this->assertCount(2, $scholar->presentations);
    }
}
