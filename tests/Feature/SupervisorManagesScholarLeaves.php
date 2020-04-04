<?php

namespace Tests\Feature;

use App\Leave;
use App\LeaveStatus;
use App\Scholar;
use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupervisorManagesScholarLeaves extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_update_leave_status_for_scholar()
    {
        $teacher = create(Teacher::class);
        $supervisorProfile = $teacher->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);
        $leave = create(Leave::class, 1, ['scholar_id' => $scholar->id]);

        $this->signInTeacher($teacher);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.leaves.update', [$scholar, $leave]), [
                'status' => LeaveStatus::APPROVED,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertTrue($leave->fresh()->isApproved());
    }

    /** @test */
    public function supervisor_update_leave_status_to_rejected_for_scholar()
    {
        $teacher = create(Teacher::class);
        $supervisorProfile = $teacher->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);
        $leave = create(Leave::class, 1, ['scholar_id' => $scholar->id]);

        $this->signInTeacher($teacher);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.leaves.update', [$scholar, $leave]), [
                'status' => LeaveStatus::REJECTED,
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertEquals(LeaveStatus::REJECTED, $leave->fresh()->status);
    }
}
