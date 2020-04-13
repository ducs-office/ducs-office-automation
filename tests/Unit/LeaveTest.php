<?php

namespace Tests\Unit;

use App\Models\Leave;
use App\Models\Scholar;
use App\Types\LeaveStatus;
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
        $scholar = create(Scholar::class);
        $leave = create(Leave::class, 1, [
            'status' => LeaveStatus::APPLIED,
            'scholar_id' => $scholar->id,
        ]);

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

    /** @test */
    public function nextExtensionsFrom_method_returns_next_date_of_last_approved_Extension()
    {
        $leave = create(Leave::class, 1, [
            'from' => now(),
            'status' => LeaveStatus::APPLIED,
            'to' => $to = now()->addDays(2),
        ]);

        $this->assertNull($leave->nextExtensionFrom());

        $leave->recommend();

        $this->assertNull($leave->nextExtensionFrom());

        $leave->approve();

        $expectedNextDate = $to->addDay();
        $this->assertEquals(
            $expectedNextDate->toDateString(),
            $leave->fresh()->nextExtensionFrom()->toDateString()
        );

        $extension = create(Leave::class, 1, [
            'extended_leave_id' => $leave->id,
            'to' => $leave->nextExtensionFrom()->addDays(5),
        ]);
        $this->assertEquals(
            $expectedNextDate->toDateString(),
            $leave->fresh()->nextExtensionFrom()->toDateString()
        );

        $extension->approve();

        $expectedNextDate = $extension->to->addDay();
        $this->assertEquals(
            $expectedNextDate->toDateString(),
            $leave->fresh()->nextExtensionFrom()->toDateString()
        );
    }
}
