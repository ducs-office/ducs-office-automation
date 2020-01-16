<?php

namespace Tests\Feature;

use App\Programme;
use App\Course;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_course_code()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(Course::class);

        $response = $this->patch('/courses/'.$course->id, [
            'code' => $newCode = 'New123'
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, Course::count());
        $this->assertEquals($newCode, $course->fresh()->code);
    }

    /** @test */
    public function admin_can_update_course_name()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(Course::class);

        $response = $this->patch('/courses/' . $course->id, [
            'name' => $newName = 'New course'
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, Course::count());
        $this->assertEquals($newName, $course->fresh()->name);
    }

    /** @test */
    public function course_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(Course::class);

        $response = $this->patch('/courses/'.$course->id, [
            'code' => $course->code,
            'name' => $newName = 'New course'
        ])->assertRedirect('/courses')
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'Course updated successfully!');


        $this->assertEquals(1, Course::count());
        $this->assertEquals($newName, $course->fresh()->name);
    }

    /** @test */
    public function admin_can_update_type_of_course()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(Course::class, 1, ['type' => 'C']);

        $this->patch('/courses/' . $course->id, [
            'type' => $newType = 'OE'
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, Course::count());
        $this->assertEquals($newType, $course->fresh()->type);
    }

    /** @test */
    public function uploading_attachments_when_updating_course_attaches_to_the_latest_course_revision()
    {
        Storage::fake();

        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(Course::class, 1, ['type' => 'C']);

        $oldRevision = $course->revisions()->create([
            'revised_at' => now()->subYears(2)
        ]);

        $oldRevision->attachments()->create([
            'original_name' => 'old_syllabus.pdf',
            'path' => UploadedFile::fake()->create('old_syllabus.pdf')->store('course_attachments')
        ]);

        $newRevision = $course->revisions()->create([
            'revised_at' => now()->subYears(1)
        ]);

        $newRevision->attachments()->create([
            'original_name' => 'new_syllabus.pdf',
            'path' => UploadedFile::fake()->create('new_syllabus.pdf')->store('courses_attachments')
        ]);

        $this->patch('/courses/' . $course->id, [
            'attachments' => [
                $newDoc = UploadedFile::fake()->create('newFile.pdf')
            ]
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertCount(1, $oldRevision->fresh()->attachments);
        $this->assertCount(2, $newRevision->fresh()->attachments);
        $this->assertEquals(
            $newDoc->getClientOriginalName(),
            $newRevision->fresh()->attachments[1]->original_name
        );
    }
}
