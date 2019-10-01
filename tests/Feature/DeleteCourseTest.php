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
    public function admin_can_delete_any_course()
    {
        $course = factory(Course::class)->create();
        $this->be(factory(User::class)->create());

        $this->delete('/courses/'.$course->id)
            ->assertRedirect('courses')
            ->assertSessionHasFlash('success', 'Course deleted successfully!');
        
        $this->assertNull($course->fresh(), 'Course still exists in database.');
    }
}
