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
                'subjects',
            ]);
    }
}
