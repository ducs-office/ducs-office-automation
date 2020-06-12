<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarProfile;
use App\Models\Teacher;
use App\Models\User;
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

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $response = $this->withoutExceptionHandling()
            ->get(route('scholars.profile.show', $scholar))
            ->assertSuccessful()
            ->assertViewHasAll([
                'scholar', 'eventTypes',
            ])
            ->assertSee($scholar->email);
    }
}
