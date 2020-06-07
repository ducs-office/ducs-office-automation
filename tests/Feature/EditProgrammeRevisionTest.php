<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditProgrammeRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function programme_revision_can_be_edited()
    {
        $this->signIn();

        $programme = create(Programme::class);
        $revision = create(ProgrammeRevision::class, 1, [
            'programme_id' => $programme->id,
        ]);
        $semesterCourses = create(Course::class, 2);

        foreach ($semesterCourses as $index => $course) {
            $course->programmeRevisions()->attach($revision, ['semester' => $index + 1]);
        }

        $this->withoutExceptionHandling()
            ->get(route('staff.programmes.revisions.edit', [
                'programme' => $programme,
                'revision' => $revision,
            ]))
            ->assertSuccessful()
            ->assertViewIs('staff.programmes.revisions.edit')
            ->assertViewHas('semesterCourses')
            ->assertViewHas('revision')
            ->assertViewHas('programme');
    }

    /** @test */
    public function guest_cannot_edit_any_programme_revision()
    {
        $this->expectException(AuthenticationException::class);

        $programme = create(Programme::class);
        $revision = create(ProgrammeRevision::class, 1, [
            'programme_id' => $programme->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('staff.programmes.revisions.edit', [
                'programme' => $programme,
                'revision' => $revision,
            ]));
    }
}
