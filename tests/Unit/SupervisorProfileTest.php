<?php

namespace Tests\Unit;

use App\Scholar;
use App\SupervisorProfile;
use App\Teacher;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_profile_belongs_to_a_supervisor()
    {
        $teacher = create(Teacher::class);

        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => $teacher->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $supervisorProfile->supervisor());
        $this->assertTrue($supervisorProfile->supervisor->is($teacher));
    }

    /** @test */
    public function supervisor_profile_has_many_scholars_under_them()
    {
        $teacher = create(Teacher::class);

        $supervisorProfile = create('App\SupervisorProfile', 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => $teacher->id,
        ]);

        $this->assertInstanceOf(HasMany::class, $supervisorProfile->scholars());
        $this->assertCount(0, $supervisorProfile->scholars);

        $scholars = create(Scholar::class, 4, [
            'supervisor_profile_id' => $supervisorProfile->id,
        ]);

        $this->assertCount(4, $supervisorProfile->fresh()->scholars);
    }
}
