<?php

namespace Tests\Feature;

use App\Models\Leave;
use App\Models\LeaveStatus;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupervisorManagesScholarLeaves extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_recommends_leave_for_scholar()
    {
        $teacher = create(Teacher::class);
        $supervisorProfile = $teacher->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);
        $leave = create(Leave::class, 1, ['scholar_id' => $scholar->id]);

        $this->signInTeacher($teacher);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.leaves.recommend', [$scholar, $leave]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertTrue($leave->fresh()->isRecommended());
    }

    /** @test */
    public function superviser_cannot_recommend_leave_for_scholar_whom_they_donot_supervise()
    {
        $anotherSupervisor = create(Teacher::class);
        $anotherSupervisorProfile = $anotherSupervisor->supervisorProfile()->create();
        $actualSupervisorProfile = create(SupervisorProfile::class);

        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $anotherSupervisorProfile->id]);
        $leave = create(Leave::class, 1, ['scholar_id' => $scholar->id]);

        $this->signInTeacher($anotherSupervisor);

        $this->withExceptionHandling()
            ->patch(route('research.scholars.leaves.recommend', [$scholar, $leave]))
            ->assertForbidden();

        $this->assertFalse($leave->fresh()->isRecommended());
        $this->assertEquals($leave->status, $leave->fresh()->status);
    }
}
