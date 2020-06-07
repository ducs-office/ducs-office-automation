<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRevision;
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
                'revision' => $newRevision,
            ]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertNull($newRevision->fresh());
        $this->assertNull($attachment->fresh());

        Storage::assertMissing($attachment->path);
    }

    /** @test */
    public function all_revisions_of_a_course_can_not_be_deleted()
    {
        Storage::fake();

        $course = create(Course::class);

        $revisions = $course->revisions()->createMany([
            ['revised_at' => now()],
            ['revised_at' => now()->subYears(2)],
        ]);

        $attachments = [];

        foreach ($revisions as $index => $revision) {
            $attachments[$index] = $revision->attachments()->create([
                'original_name' => 'file.pdf',
                'path' => UploadedFile::fake()->create('file.pdf')->store('some_place'),
            ]);
        }

        $this->signIn();

        $this->withoutExceptionHandling()
            ->delete(route('staff.courses.revisions.destroy', [
                'course' => $course,
                'revision' => $revisions[0],
            ]))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->withExceptionHandling()
            ->delete(route('staff.courses.revisions.destroy', [
                'course' => $course,
                'revision' => $revisions[1],
            ]))
            ->assertForbidden();

        $this->assertNull($revisions[0]->fresh());
        Storage::assertMissing($attachments[0]->path);

        $this->assertEquals(1, $course->revisions->count());
        $this->assertEquals(1, CourseRevision::count());
        $this->assertEquals(1, Course::count());
        Storage::assertExists($attachments[1]->path);
    }
}
