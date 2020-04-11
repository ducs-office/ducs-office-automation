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
                'admissionCriterias',
                'genders',
                'categories',
                'eventTypes',
            ])
        ->assertSee($scholar->email);
    }
}
