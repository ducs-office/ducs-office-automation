<?php

namespace Tests\Unit;

use App\Leave;
use App\LeaveStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function is_approved_method_checks_leave_status_if_approved()
    {
        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->assertFalse($leave->isApproved());

        $leave->approve();

        $this->assertTrue($leave->isApproved());
    }
}
