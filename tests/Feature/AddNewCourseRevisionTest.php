<?php

namespace Tests\Feature;

use App\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddNewCourseRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_revision_can_be_added_to_the_course()
    {
        $this->signIn();

        Storage::fake();

        $course = create(Course::class);

        $course->revisions()->create([
            'revised_at' => now(),
        ])->attachments()->create([
            'original_name' => 'filename.jpg',
            'path' => UploadedFile::fake()->image('filename.jpg')->store('/course_attachments')
        ]);

        $this->withoutExceptionHandling()
            ->post('/courses/' . $course->id . '/revisions', $revision = [
                'revised_at' => now()->subMonth(12)->format('Y-m-d'),
                'attachments' => [
                    UploadedFile::fake()->create('New Doc.pdf')
                ]
            ])->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertCount(2, $course->fresh()->revisions);
        $this->assertEquals(
            $revision['revised_at'],
            $course->fresh()->revisions->get(1)->revised_at->format('Y-m-d')
        );
        $this->assertCount(1, $course->fresh()->revisions->get(1)->attachments);
    }
}
