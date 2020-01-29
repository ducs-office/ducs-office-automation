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
                'programmeRevision' => $revision,
            ]))
            ->assertRedirect();
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
            ->delete(route('staff.programmes.revisions.destroy', [
                    'programme' => $programme,
                    'programmeRevision' => $revision
                ]))
            ->assertRedirect();

        $this->assertEquals(0, $revision->courses->count());
        $this->assertEquals(0, ProgrammeRevision::count());
    }

    /** @test */
    public function programme_is_deleted_if_all_its_revisions_have_been_deleted()
    {
        $this->signIn();

        $programme = create('App\Programme');
        $courses = create('App\Course', 2);

        $revisions = $programme->revisions()->createMany([
            ['revised_at' => $programme->wef],
            ['revised_at' => $programme->wef->addYear()]
        ]);

        foreach ($revisions as $index => $revision) {
            $revision->courses()->attach($courses[$index], ['semester' => 1]);
        }

        $this->withExceptionHandling()
            ->delete(route('staff.programmes.revisions.destroy', [
                'programme' => $programme,
                'programmeRevision' => $revisions[0],
            ]))
            ->assertRedirect();

        $this->withExceptionHandling()
        ->delete(route('staff.programmes.revisions.destroy', [
            'programme' => $programme,
            'programmeRevision' => $revision
        ]))
        ->assertRedirect();

        $this->assertEquals(0, $revision->courses->count());
        $this->assertEquals(0, ProgrammeRevision::count());
        $this->assertEquals(0, Programme::count());
    }
}
