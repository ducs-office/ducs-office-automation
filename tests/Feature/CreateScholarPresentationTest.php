<?php

namespace Tests\Feature;

use App\Models\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateScholarPresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_presentation_can_be_created()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->withoutExceptionHandling()
            ->get(route('scholars.presentation.create', $scholar))
            ->assertSuccessful()
            ->assertViewIs('presentations.create')
            ->assertViewHasAll(['eventTypes']);
    }
}
