<?php

namespace Tests\Feature;

use App\Programme;
use App\Course;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_course_and_assign_it_to_a_programme()
    {
        $this->withoutExceptionHandling()
            ->be(create(User::class));

        $programme = create(Programme::class);

        $this->post('/courses', $params = [
            'code' => 'MCS-102',
            'name' => 'Design and Analysis of Algorithms',
            'programme_id' => $programme->id,
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course created successfully!');

        $this->assertEquals(1, Course::count());

        tap(Course::first(), function($course) use ($params) {
            foreach($params as $param => $value) {
                $this->assertEquals($value, $course->{$param});
            }
        });

    }
}
