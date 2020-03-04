<?php

namespace Tests\Feature;

use App\Scholar;
use App\ScholarProfile;
use App\SupervisorProfile;
use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScholarsProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_view_their_profile()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $response = $this->withoutExceptionHandling()
            ->get(route('scholars.profile'))
            ->assertSuccessful()
            ->assertViewHasAll([
                'scholar',
                'categories',
                'admission_criterias',
            ])
        ->assertSee($scholar->email);
    }

    /** @test */
    public function view_has_unique_list_of_supervisors()
    {
        $this->signInScholar(create(Scholar::class));

        $supervisors = create(SupervisorProfile::class, 3);

        $viewData = $this->withoutExceptionHandling()
            ->get(route('scholars.profile'))
            ->assertSuccessful()
            ->assertViewIs('scholars.profile')
            ->assertViewHas('supervisors')
            ->viewData('supervisors');

        $this->assertCount(3, $viewData);
        $this->assertSame($supervisors->pluck('id', 'supervisor.name')->toArray(), $viewData->toArray());
    }
}
