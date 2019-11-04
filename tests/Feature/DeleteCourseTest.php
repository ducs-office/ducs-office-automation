<?php

namespace Tests\Feature;

use App\Course;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_course()
    {
        $this->signIn();

        $course = create(Course::class);

        $this->withoutExceptionHandling()
            ->delete('/courses/'.$course->id)
            ->assertRedirect('/courses')
            ->assertSessionHasFlash('success', 'Course deleted successfully!');

        $this->assertNull($course->fresh(), 'Course was not deleted!');
    }
}
