<?php

namespace Tests\Feature;

use App\Models\SupervisorProfile;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSupervisorPublicationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_can_view_publications()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => create(Teacher::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signInTeacher($supervisor);

        $response = $this->withoutExceptionHandling()
                ->get(route('research.publications.index'))
                ->assertViewHas('supervisor');
    }
}
