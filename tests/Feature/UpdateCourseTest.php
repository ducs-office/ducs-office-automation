<?php

namespace Tests\Feature;

use App\Course;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCourseTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function admin_can_update_course_code()
    {
        $this->be(factory(User::class)->create())
            ->withoutExceptionHandling();
        
        $course = factory(Course::class)->create();

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
        $this->be(factory(User::class)->create())
            ->withoutExceptionHandling();
        
        $course = factory(Course::class)->create();

        $response = $this->patch('/courses/'.$course->id, [
            'name' => $newName = 'New Course'
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, Course::count());
        $this->assertEquals($newName, $course->fresh()->name);
    }

    /** @test */
    public function course_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->be(factory(User::class)->create())
            ->withoutExceptionHandling();
        
        $course = factory(Course::class)->create();

        $response = $this->patch('/courses/'.$course->id, [
            'code' => $course->code,
            'name' => $newName = 'New Course'
        ])->assertRedirect('/courses')
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'Course updated successfully!');


        $this->assertEquals(1, Course::count());
        $this->assertEquals($newName, $course->fresh()->name);
    }
}
