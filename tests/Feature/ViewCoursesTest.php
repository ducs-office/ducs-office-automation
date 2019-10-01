<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCoursesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_courses()
    {
        $courses = factory('App\Course', 3)->create();

        $this->withExceptionHandling();

        $this->get('/courses')->assertRedirect('/login');
    }
    /** @test */
    public function admin_can_view_all_courses()
    {
        $this->be(factory(User::class)->create());

        $courses = factory('App\Course', 3)->create();

        $this->withoutExceptionHandling();

        $viewData = $this->get('/courses')->assertViewIs('courses.index')
            ->assertViewHas('courses')
            ->viewData('courses');

        $this->assertCount(3, $viewData);
        $this->assertTrue($courses[2]->is($viewData[2])); //first created is at last
    }
}
