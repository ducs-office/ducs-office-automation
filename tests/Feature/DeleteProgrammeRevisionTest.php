<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteProgrammeRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_deleted_a_programme_revision()
    {
        $programme = create(Programme::class);
        $revision = $programme->revisions()->create(['revised_at' => now()]);

        $this->withExceptionHandling()
            ->delete(route('staff.programmes.revisions.destroy', [
                'programme' => $programme,
                'revision' => $revision,
            ]))
            ->assertRedirect();
    }

    /** @test */
    public function all_revisions_of_a_programme_cannot_be_deleted()
    {
        $this->signIn();

        $programme = create(Programme::class);
        $courses = create(Course::class, 2);

        $revisions = $programme->revisions()->createMany([
            ['revised_at' => now()],
            ['revised_at' => now()->addYear()],
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
