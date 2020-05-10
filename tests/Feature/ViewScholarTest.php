<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
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
    public function a_supervisor_can_view_only_scholars_whom_they_supervise_even_without_explicit_permission()
    {
        $teacher = create(User::class);
        $teacher->revokePermissionTo('scholars:view');

        $supervisorProfile = $teacher->supervisorProfile()->create();
        $theirScholars = create(Scholar::class, 3, ['supervisor_profile_id' => $supervisorProfile->id]);
        $otherScholars = create(Scholar::class, 5);

        $this->signIn($teacher, null);
        $scholars = $this->withoutExceptionHandling()
            ->get(route('research.scholars.index'))
            ->assertViewHas('scholars')
            ->viewData('scholars');

        $this->assertCount(3, $scholars);
        $this->assertEquals($theirScholars->pluck('id'), $scholars->pluck('id'));
    }

    /** @test */
    public function a_user_cannot_view_scholars_without_permission()
    {
        create(Scholar::class, 5);

        $teacher = create(User::class, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        $teacher->revokePermissionTo('scholars:view');

        $this->signIn($teacher, null);

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
