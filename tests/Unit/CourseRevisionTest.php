<?php

namespace Tests\Unit;

use App\CourseRevision;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function course_revision_has_many_attachments()
    {
        $revision = create(CourseRevision::class);

        $this->assertTrue(
            method_exists($revision, 'attachments'),
            'CourseRevision does not have any method named "attachments"'
        );

        $this->assertInstanceOf(
            MorphMany::class,
            $revision->attachments(),
            '"attachments" method does not return a instance of "MorphMany" relationship'
        );
    }
}
