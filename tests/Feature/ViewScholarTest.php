<?php

namespace Tests\Feature;

use App\Cosupervisor;
use App\Scholar;
use App\SupervisorProfile;
use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholars_can_be_viewed()
    {
        $this->signIn();

        create(Scholar::class, 3);

        $scholars = $this->withoutExceptionHandling()
            ->get(route('staff.scholars.index'))
            ->assertViewHas('scholars')
            ->viewData('scholars');

        $this->assertCount(3, $scholars);
    }

    /** @test */
    public function a_supervisor_teacher_can_view_scholars_whom_they_supervise()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $supervisorProfile = $teacher->supervisorProfile()->create();

        $theirScholars = create(Scholar::class, 3, ['supervisor_profile_id' => $supervisorProfile->id]);
        $otherScholars = create(Scholar::class, 5);

        $scholars = $this->withoutExceptionHandling()
            ->get(route('research.scholars.index'))
            ->assertViewHas('scholars')
            ->viewData('scholars');

        $this->assertCount(3, $scholars);
        $this->assertEquals($theirScholars->pluck('id'), $scholars->pluck('id'));
    }

    /** @test */
    public function a_non_supervisor_teacher_cannot_view_scholars()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $scholars = create(Scholar::class, 5);

        $this->withExceptionHandling()
            ->get(route('research.scholars.index'))
            ->assertForbidden();
    }

    /** @test */
    public function view_has_a_unique_list_of_supervisors()
    {
        $supervisorProfiles = create(SupervisorProfile::class, 3);

        $this->signIn();

        $viewData = $this->withoutExceptionHandling()
            ->get(route('staff.scholars.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.scholars.index')
            ->assertViewHas('supervisors')
            ->viewData('supervisors');

        $this->assertCount(3, $viewData);
        $this->assertSame($supervisorProfiles->pluck('id', 'supervisor.name')->toArray(), $viewData->toArray());
    }

    /** @test */
    public function view_has_a_unique_list_of_cosupervisors()
    {
        $cosupervisors = create(Cosupervisor::class, 3);
        $this->signIn();

        $viewData = $this->withoutExceptionHandling()
             ->get(route('staff.scholars.index'))
             ->assertSuccessful()
             ->assertViewIs('staff.scholars.index')
             ->assertViewHas('cosupervisors')
             ->viewData('cosupervisors');

        $this->assertCount(Cosupervisor::count(), $viewData);
    }
}
