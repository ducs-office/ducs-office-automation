<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\PhdCourse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePhdCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_course()
    {
        $this->signIn();

        $course = create(PhdCourse::class);

        $this->withoutExceptionHandling()
            ->delete(route('staff.phd_courses.destroy', $course))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Course deleted successfully!');

        $this->assertNull($course->fresh(), 'Course was not deleted!');
    }
}
