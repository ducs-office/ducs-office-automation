<?php

namespace Tests\Feature;

use App\Programme;
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
            ->delete(route('staff.programmes.revisions.destroy', [
                'programme' => $programme,
                'revision' => $revision,
            ]))
            ->assertRedirect();
    }

    /** @test */
    public function programme_revision_can_only_be_deleted_when_there_are_more_than_2_revisions()
    {
        $this->signIn();

        $programme = create('App\Programme');

        $revisions = $programme->revisions()->createMany([
            ['revised_at' => now()->subYear()],
            ['revised_at' => now()],
        ]);

        $this->withExceptionHandling()
            ->delete(route('staff.programmes.revisions.destroy', [
                'programme' => $programme,
                'revision' => $revisions[0],
            ]))
            ->assertRedirect();

        $this->assertNull($revisions[0]->fresh());
        $this->assertEquals(1, ProgrammeRevision::count());
    }

    /** @test */
    public function all_revisions_of_a_programme_cannot_be_deleted()
    {
        $this->signIn();

        $programme = create('App\Programme');
        $courses = create('App\Course', 2);

        $revisions = $programme->revisions()->createMany([
            ['revised_at' => $programme->wef],
            ['revised_at' => $programme->wef->addYear()],
        ]);

        foreach ($revisions as $index => $revision) {
            $revision->courses()->attach($courses[$index], ['semester' => 1]);
        }

        $this->withoutExceptionHandling()
            ->delete(route('staff.programmes.revisions.destroy', [
                'programme' => $programme,
                'revision' => $revisions[0],
            ]))
            ->assertRedirect();

        $this->withExceptionHandling()
        ->delete(route('staff.programmes.revisions.destroy', [
            'programme' => $programme,
            'revision' => $revisions[1],
        ]))
        ->assertForbidden();

        $this->assertEquals(0, $revisions[0]->courses()->count());
        $this->assertNull($revisions[0]->fresh());
        $this->assertEquals(1, $revisions[1]->courses()->count());
        $this->assertNotNull($revisions[1]->fresh());
        $this->assertEquals(1, ProgrammeRevision::count());
        $this->assertEquals(1, Programme::count());
    }
}
