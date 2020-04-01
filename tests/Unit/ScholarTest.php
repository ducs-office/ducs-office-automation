<?php

namespace Tests\Unit;

use App\Leave;
use App\PhdCourse;
use App\Scholar;
use App\SupervisorProfile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
            'page_numbers' => [23, 80],
            'date' => '2019-07-12',
            'number' => '221109',
            'city' => 'Moscow',
            'country' => 'Russia',
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

    /** @test */
    public function scholar_belongs_to_a_supervisor_profile()
    {
        $supervisorProfile = create(SupervisorProfile::class);

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisorProfile->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $scholar->supervisorProfile());
        $this->assertTrue($supervisorProfile->is($scholar->supervisorProfile));
    }

    /** @test */
    public function scholar_morphs_to_a_supervisor_indirectly_through_supervisor_profile()
    {
        $supervisorProfile = create(SupervisorProfile::class);

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisorProfile->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $scholar->supervisor());
        $this->assertTrue($supervisorProfile->supervisor->is($scholar->supervisor));
    }

    /** @test */
    public function scholar_has_many_pre_phd_courseworks()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(BelongsToMany::class, $scholar->courseworks());
        $this->assertCount(0, $scholar->courseworks);

        $scholar->courseworks()->attach(create(PhdCourse::class));

        $this->assertCount(1, $scholar->fresh()->courseworks);
    }

    /** @test */
    public function scholar_has_many_leaves()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->leaves());
        $this->assertCount(0, $scholar->leaves);

        $leaves = create(Leave::class, 2, ['scholar_id' => $scholar->id]);

        $this->assertCount(count($leaves), $scholar->fresh()->leaves);
        $this->assertEquals($leaves->pluck('id'), $scholar->fresh()->leaves->pluck('id'));
    }

    /** @test */
    public function all_core_courseworks_are_added_to_scholar_when_scholar_is_created()
    {
        $coreCourseworks = create(PhdCourse::class, 2, ['type' => 'C']);
        $electiveCourseworks = create(PhdCourse::class, 2, ['type' => 'E']);

        $scholar = create(Scholar::class);

        $this->assertCount(2, $scholar->courseworks);
        $this->assertEquals(
            $coreCourseworks->pluck('id'),
            $scholar->courseworks->pluck('id')
        );
    }
}
