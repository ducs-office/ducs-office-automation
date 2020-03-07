<?php

namespace Tests\Unit;

use App\Scholar;
use App\ScholarProfile;
use App\SupervisorProfile;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_has_one_profile()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasOne::class, $scholar->profile());
        $this->assertInstanceOf(ScholarProfile::class, $scholar->profile);
    }

    /** @test */
    public function scholar_profile_is_created_on_creating_new_scholar()
    {
        $scholar = create(Scholar::class);

        $this->assertEquals(1, ScholarProfile::count());
        $this->assertEquals($scholar->id, ScholarProfile::first()->scholar_id);
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

    }
}
