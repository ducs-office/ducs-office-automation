<?php

namespace Tests\Unit;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gives_full_name()
    {
        $teacher = new Teacher();
        $teacher->first_name = 'John';
        $teacher->last_name = 'Doe';

        $this->assertEquals('John Doe', $teacher->name);
    }

    /** @test */
    public function it_may_have_a_supervisor_profile()
    {
        $teacher = create(Teacher::class);

        $this->assertInstanceOf(MorphOne::class, $teacher->supervisorProfile());
        $this->assertNull($teacher->supervisorProfile);

        $profile = $teacher->supervisorProfile()->create();

        $this->assertTrue($profile->is($teacher->fresh()->supervisorProfile));
    }

    /** @test */
    public function isSupervisor_method_gives_boolean_indicating_whether_or_not_user_is_a_supervisor()
    {
        $teacher = create(Teacher::class);

        $this->assertFalse($teacher->isSupervisor());

        $profile = $teacher->supervisorProfile()->create();

        $this->assertTrue($teacher->fresh()->isSupervisor());
    }
}
