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
        $supervisorProfile = create(SupervisorProfile::class);
        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);

        $response = $this->withoutExceptionHandling()
                ->get(route('research.publications.index'))
                ->assertViewHas('supervisor');
    }
}
