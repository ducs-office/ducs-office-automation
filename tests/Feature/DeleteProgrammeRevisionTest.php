<?php

namespace Tests\Feature;

use App\ProgrammeRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteProgrammeRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_deleted_a_programme_revision()
    {
        $programme = create('App\Programme');
        $revision = $programme->revisions()->create(['revised_at' => now()]);

        $this->withExceptionHandling()
            ->delete("/programme/{$programme->id}/revisions/{$revision->id}")
            ->assertRedirect('/login');
    }

    /** @test */
    public function programme_revision_can_be_deleted()
    {
        $this->signIn();

        $programme = create('App\Programme');
        $course = create('App\Course');

        $revision = $programme->revisions()->create(['revised_at' => now()]);
        $revision->courses()->attach($course, ['semester' => 1]);

        $this->withExceptionHandling()
            ->delete("/programme/{$programme->id}/revisions/{$revision->id}")
            ->assertRedirect("/programme/{$programme->id}/revisions");

        $this->assertEquals(0, $revision->courses->count());
        $this->assertEquals(0, ProgrammeRevision::count());
    }
}
