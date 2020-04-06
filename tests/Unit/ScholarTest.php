<?php

namespace Tests\Unit;

use App\AdvisoryMeeting;
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

    protected function fillPublication($overrides = [])
    {
        return $this->mergeFormFields([
            'type' => null,
            'name' => 'India CS Journal',
            'authors' => ['JOhn Doe', 'Sally Brooke'],
            'paper_title' => 'Lorem ipsum dolor sit amet consectetur adipisicing',
            'date' => '2020-02-09',
            'volume' => '1',
            'page_numbers' => ['23', '80'],
            'indexed_in' => ['Scopus', 'SCI'],
            'number' => null,
            'publisher' => null,
            'city' => null,
            'country' => null,
        ], $overrides);
    }

    /** @test */
    public function scholar_has_many_publications()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->publications());

        $scholar->publications()->createMany([
            $this->fillPublication([
                'type' => 'journal',
                'number' => 123,
                'publisher' => 'O Reilly',
            ]),
            $this->fillPublication([
                'type' => 'conference',
                'city' => 'Delhi',
                'country' => 'India',
            ]),
        ]);

        $this->assertCount(2, $scholar->publications);
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
        create(Leave::class, 2, ['scholar_id' => $scholar->id, 'extended_leave_id' => $leaves[0]->id]);

        $this->assertCount(count($leaves), $scholar->fresh()->leaves);
        $this->assertEquals($leaves->sortByDesc('to')->pluck('id'), $scholar->fresh()->leaves->pluck('id'));
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

    /** @test */
    public function scholar_has_many_advisory_meetings()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->advisoryMeetings());
        $this->assertCount(0, $scholar->advisoryMeetings);

        $meeting = create(AdvisoryMeeting::class, 1, ['scholar_id' => $scholar->id]);

        $this->assertCount(1, $scholar->fresh()->advisoryMeetings);
    }
}
