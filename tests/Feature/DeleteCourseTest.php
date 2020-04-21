<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
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
            ->delete(route('staff.courses.destroy', $course))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Course deleted successfully!');

        $this->assertNull($course->fresh(), 'Course was not deleted!');
    }
}
