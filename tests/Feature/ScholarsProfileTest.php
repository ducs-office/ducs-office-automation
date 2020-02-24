<?php

namespace Tests\Feature;

use App\Scholar;
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
                'admission_via',
            ]);
        // ->assertSee($scholar->email);
    }
}
