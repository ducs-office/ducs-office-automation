<?php

namespace Tests\Unit;

use App\Models\Leave;
use App\Models\LeaveStatus;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /** @test */
    public function is_recommended_method_checks_leave_status_if_recommended_by_supervisor()
    {
        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->assertFalse($leave->isRecommended());

        $leave->recommend();

        $this->assertTrue($leave->isRecommended());
    }

    /** @test */
    public function leave_has_many_extension_leaves()
    {
        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->assertInstanceOf(HasMany::class, $leave->extensions());

        $extensionLeaves = create(Leave::class, 3, ['extended_leave_id' => $leave->id]);

        $this->assertCount(3, $leave->fresh()->extensions);
        $this->assertEquals($extensionLeaves->pluck('id'), $leave->fresh()->extensions->pluck('id'));
    }
}
