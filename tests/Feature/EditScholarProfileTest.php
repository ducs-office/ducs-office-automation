<?php

namespace Tests\Feature;

use App\Cosupervisor;
use App\Scholar;
use App\SupervisorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditScholarProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_edit_their_profile()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
            ->get(route('scholars.profile.edit'))
            ->assertSuccessful()
            ->assertViewIs('scholars.edit')->assertViewHasAll([
                'scholar',
                'categories',
                'admissionCriterias',
                'genders',
                'supervisorProfiles',
                'cosupervisors',
                'subjects',
            ]);
    }

    /** @test */
    public function sholar_edit_view_has_a_unique_list_of_supervisors()
    {
        $supervisorProfiles = create(SupervisorProfile::class, 3);

        $this->signInScholar(create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfiles[0]->id]));

        $viewData = $this->withoutExceptionHandling()
            ->get(route('scholars.profile.edit'))
            ->assertSuccessful()
            ->assertViewIs('scholars.edit')
            ->assertViewHas('supervisorProfiles')
            ->viewData('supervisorProfiles');

        $this->assertCount(3, $viewData);
        $this->assertSame($supervisorProfiles->pluck('id', 'supervisor.name')->toArray(), $viewData->toArray());
    }

    /** @test */
    public function sholar_edit_view_has_a_unique_list_of_cosupervisors()
    {
        $cosupervisors = create(Cosupervisor::class, 3);
        $this->signInScholar(create(Scholar::class));

        $viewData = $this->withoutExceptionHandling()
             ->get(route('scholars.profile.edit'))
             ->assertSuccessful()
             ->assertViewIs('scholars.edit')
             ->assertViewHas('cosupervisors')
             ->viewData('cosupervisors');

        $this->assertCount(Cosupervisor::count(), $viewData);
    }
}
