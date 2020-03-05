<?php

namespace Tests\Unit;

use App\Scholar;
use App\ScholarProfile;
use App\Teacher;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ScholarTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function advisorFields($count = 1, $overrides = [])
    {
        $advisors = [];

        for ($x = 0; $x < $count; $x++) {
            $advisor = [
                'title' => $this->faker->title,
                'name' => $this->faker->name,
                'designation' => $this->faker->jobTitle,
                'affiliation' => $this->faker->company,
                'type' => $this->faker->randomElement(['A', 'C']),
            ];

            $advisor = array_merge($advisor, $overrides);
            array_push($advisors, $advisor);
        }

        return $advisors;
    }

    // /** @test */
    // public function scholar_has_a_supervisor()
    // {
    //     $teacher = create(Teacher::class);

    //     $scholar = create(Scholar::class);

    //     $scholar->profile()->update([
    //         'supervisor_type' => Teacher::class,
    //         'supervisor_id' => $teacher->id,
    //     ]);

    //     $this->assertInstanceOf(BelongsTo::class, $scholar->profile->supervisor());
    //     $this->assertTrue($scholar->profile->supervisor->is($teacher));
    // }

    /** @test */
    public function scholar_may_have_many_advisors()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->advisors());
        $this->assertCount(0, $scholar->advisors);

        $advisorFieldsArray = $this->advisorFields(3);
        $advisors = $scholar->advisors()->createMany($advisorFieldsArray);

        // $this->assertTrue($advisors->is($scholar->profile->fresh()->advisors));

        $this->assertCount(3, $scholar->fresh()->advisors);
    }

    /** @test */
    public function scholar_may_have_an_advisory_committe()
    {
        $scholar = create(Scholar::class);

        $this->assertEquals(0, $scholar->advisoryCommittee->count());

        $advisoryCommitteeFieldsArray = $this->advisorFields(3, ['type' => 'A']);
        $advisoryCommittee = $scholar->advisors()->createMany($advisoryCommitteeFieldsArray);

        $this->assertCount(3, $scholar->fresh()->advisoryCommittee);
        $this->assertEquals($advisoryCommittee[0]->name, $scholar->fresh()->advisoryCommittee[0]->name);
    }

    /** @test */
    public function scholar_may_have_co_supervisors()
    {
        $scholar = create(Scholar::class);

        $this->assertEquals(0, $scholar->advisoryCommittee->count());

        $coSupervisorsFieldsArray = $this->advisorFields(3, ['type' => 'C']);
        $coSupervisors = $scholar->advisors()->createMany($coSupervisorsFieldsArray);

        $this->assertCount(3, $scholar->fresh()->coSupervisors);
        $this->assertEquals($coSupervisors[0]->name, $scholar->fresh()->coSupervisors[0]->name);
    }
}
