<?php

namespace Tests\Unit;

use App\Publication;
use App\SupervisorProfile;
use App\Teacher;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    /** @test */
    public function supervisor_profile_has_many_publications()
    {
        $supervisorProfile = create('App\SupervisorProfile');

        $this->assertCount(0, $supervisorProfile->publications);

        $this->assertInstanceOf(MorphMany::class, $supervisorProfile->publications());

        $publication = create(Publication::class, 1, [
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->assertCount(1, $supervisorProfile->fresh()->publications);
    }
}
