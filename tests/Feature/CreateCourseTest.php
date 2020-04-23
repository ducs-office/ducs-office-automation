<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRevision;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateCourseTest extends TestCase
{
    use RefreshDatabase;

    protected function fillCoursesForm($overrides = [])
    {
        return $this->mergeFormFields([
            'code' => 'MCS-102',
            'name' => 'Design and Analysis of Algorithms',
            'type' => 'C', // Core
            'date' => now()->format('Y-m-d'),
            'attachments' => [
                UploadedFile::fake()->create('syllabus.pdf'),
                UploadedFile::fake()->create('guidelines.pdf'),
                UploadedFile::fake()->image('snapshot.jpg'),
            ],
        ], $overrides);
    }

    /** @test */
    public function admin_can_create_new_course()
    {
        $this->withoutExceptionHandling()
            ->signIn(create(User::class));

        $course = $this->fillCoursesForm();

        $this->post(route('staff.courses.store'), $course)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Course created successfully!');

        $this->assertEquals(1, Course::count());

        $newCourse = Course::first();
        $this->assertEquals($course['code'], $newCourse->code);
        $this->assertEquals($course['type'], $newCourse->type);
        $this->assertEquals($course['name'], $newCourse->name);

        $this->assertNotNull(CourseRevision::class, $revision = $newCourse->revisions->first());
        foreach ($course['attachments'] as $index => $attachment) {
            $this->assertEquals(
                'course_attachments/' . $attachment->hashName(),
                $revision->attachments[$index]->path
            );
        }
    }

    /** @test */
    public function request_validates_type_field_is_either_core_open_elective_or_general_elective()
    {
        $this->signIn();

        try {
            $course = $this->fillCoursesForm(['type' => 'random_type']);
            $this->withoutExceptionHandling()
                ->post(route('staff.courses.store'), $course);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('type', $e->errors());
        }
        $this->assertEquals(Course::count(), 0);
    }

    /** @test */
    public function course_requires_atleast_one_document_file()
    {
        $this->signIn();

        try {
            $this->post(route('staff.courses.store'), $this->fillCoursesForm([
                'attachments' => [], // no document uploaded
            ]));
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('attachments', $e->errors);
        }

        $this->assertEquals(0, Course::count());
    }

    /** @test */
    public function course_requires_a_valid_file()
    {
        $this->signIn();

        try {
            $this->post(route('staff.courses.store'), $this->fillCoursesForm([
                'attachments' => ['not a file but string'], // no document uploaded
            ]));
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('attachments', $e->errors);
        }

        $this->assertEquals(0, Course::count());
    }
}
