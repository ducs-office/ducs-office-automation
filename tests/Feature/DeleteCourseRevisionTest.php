<?php

namespace Tests\Feature;

use App\Course;
use App\CourseRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteCourseRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function course_revision_can_be_deleted()
    {
        Storage::fake();

        $course = create(Course::class);
        $oldRevision = create(CourseRevision::class, 1, [
            'revised_at' => now()->subYears(2),
            'course_id' => $course->id,
        ]);

        $newRevision = create(CourseRevision::class, 1, [
            'revised_at' => now()->subYears(1),
            'course_id' => $course->id,
        ]);

        $attachment = $newRevision->attachments()->create([
            'original_name' => 'file.pdf',
            'path' => UploadedFile::fake()->create('file.pdf'),
        ]);

        $this->signIn();

        $this->withoutExceptionHandling()
            ->delete(route('staff.courses.revisions.destroy', [
                'course' => $course,
                'course_revision' => $newRevision,
            ]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertNull($newRevision->fresh());
        $this->assertNull($attachment->fresh());

        Storage::assertMissing($attachment->path);
    }
}
