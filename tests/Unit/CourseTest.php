<?php

namespace Tests\Unit;

use App\Programme;
use App\Course;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\ProgrammeRevision;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function course_belongs_to_a_programme_revsion()
    {
        $programme = create(Programme::class);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $course = create(Course::class);
        $course->programme_revisions()->attach($revision, ['semester' => 1]);

        $this->assertInstanceOf(BelongsToMany::class, $course->programme_revisions());
        $this->assertInstanceOf(ProgrammeRevision::class, $course->programme_revisions()->first());
        $this->assertEquals($revision->id, $course->programme_revisions()->first()->id);
    }
}
