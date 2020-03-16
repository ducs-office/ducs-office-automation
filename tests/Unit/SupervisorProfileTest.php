<?php

namespace Tests\Unit;

use App\Teacher;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_profile_belongs_to_a_supervisor()
    {
        $teacher = create(Teacher::class);

        $supervisorProfile = create('App\SupervisorProfile', 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => $teacher->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $supervisorProfile->supervisor());
        $this->assertTrue($supervisorProfile->supervisor->is($teacher));
    }
}
