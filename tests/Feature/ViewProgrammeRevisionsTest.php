<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewProgrammeRevisionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test*/
    public function guest_cannot_view_programme_revisions()
    {
        $programme = create('App\Programme');

        $this->withExceptionHandling()
            ->get("/programmes/{$programme}")
            ->assertRedirect('/login');
    }

    /** @test*/
    public function programme_revisions_can_be_viewed()
    {
        $programme = create('App\Programme');
        $programme->revisions()->attach(['revised_at' => now()])->courses()->attach($course, ['semester' => 1]);
    }
}
