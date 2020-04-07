<?php

namespace Tests\Feature;

use App\Leave;
use App\LeaveStatus;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DrcMemberTakesActionOnScholarLeavesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_approve_leaves_when_they_have_permission_to_approve()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('leaves:approve');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.leaves.approve', [$leave->scholar_id, $leave]))
            ->assertRedirect();

        $this->assertTrue($leave->fresh()->isApproved());
    }

    /** @test */
    public function user_cannot_approve_leaves_when_they_donot_have_permission_to_approve()
    {
        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('leaves:approve');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withExceptionHandling()
            ->patch(route('research.scholars.leaves.approve', [$leave->scholar_id, $leave]))
            ->assertForbidden();

        $this->assertFalse($leave->fresh()->isApproved());
    }

    /** @test */
    public function user_can_reject_leaves_when_they_have_permission_to_reject()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('leaves:reject');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.leaves.reject', [$leave->scholar_id, $leave]))
            ->assertRedirect();

        $this->assertEquals(LeaveStatus::REJECTED, $leave->fresh()->status);
    }

    /** @test */
    public function user_cannot_reject_leaves_when_they_donot_have_permission_to_reject()
    {
        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('leaves:reject');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withExceptionHandling()
            ->patch(route('research.scholars.leaves.reject', [$leave->scholar_id, $leave]))
            ->assertForbidden();

        $this->assertNotEquals(LeaveStatus::REJECTED, $leave->fresh()->status);
    }
}
