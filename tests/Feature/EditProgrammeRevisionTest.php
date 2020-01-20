<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use App\Programme;
use App\ProgrammeRevision;
use App\Course;

class EditprogrammeRevision extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function programme_revision_can_be_edited()
    {
        $this->signIn();

        $programme = create(Programme::class);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $semester_courses = create(Course::class, 2);

        foreach ($semester_courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }

        $this->withoutExceptionHandling()
            ->get("/programmes/$programme->id/revision/$revision->id/edit")
            ->assertSuccessful()
            ->assertViewIs('programmes.revisions.edit')
            ->assertViewHas('semester_courses')
            ->assertViewHas('programme_revision')
            ->assertViewHas('programme');
    }

    /** @test */
    public function guest_cannot_edit_any_programme_revision()
    {
        $this->expectException(AuthenticationException::class);

        $programme = create(Programme::class);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);

        $this->withoutExceptionHandling()
            ->get("/programmes/$programme->id/revision/$revision->id/edit");
    }

    /** @test */
    public function revision_id_should_be_programmes_revision_id()
    {
        $this->signIn();

        $programme1 = create(Programme::class);
        $revision1 = create(ProgrammeRevision::class, 1, ['revised_at' => $programme1->wef, 'programme_id' => $programme1->id]);

        $programme2 = create(Programme::class);
        $revision2 = create(ProgrammeRevision::class, 1, ['revised_at' => $programme2->wef, 'programme_id' => $programme2->id]);

        $this->withoutExceptionHandling()
            ->get("/programmes/$programme1->id/revision/$revision2->id/edit")
            ->assertRedirect('/programmes');
    }
}
