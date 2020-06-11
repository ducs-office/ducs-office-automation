<?php

namespace Tests\Feature;

use App\Models\Leave;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use App\Types\LeaveStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupervisorManagesScholarLeaves extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_recommends_leave_for_scholar()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);

        $leave = create(Leave::class, 1, ['scholar_id' => $scholar->id]);

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.leaves.recommend', [$scholar, $leave]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertTrue($leave->fresh()->isRecommended());
    }

    /** @test */
    public function superviser_cannot_recommend_leave_for_scholar_whom_they_donot_supervise()
    {
        $anotherSupervisor = factory(User::class)->states('supervisor')->create();
        $actualSupervisor = factory(User::class)->states('supervisor')->create();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($actualSupervisor);

        $leave = create(Leave::class, 1, ['scholar_id' => $scholar->id]);

        $this->signIn($anotherSupervisor);

        $this->withExceptionHandling()
            ->patch(route('scholars.leaves.recommend', [$scholar, $leave]))
            ->assertForbidden();

        $this->assertFalse($leave->fresh()->isRecommended());
        $this->assertEquals($leave->status, $leave->fresh()->status);
    }
}
