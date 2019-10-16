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
        $courses = create('App\Course', 3);

        $this->withExceptionHandling();

        $this->get('/courses')->assertRedirect('/login');
    }
    /** @test */
    public function admin_can_view_all_courses()
    {
        $this->signIn();

        $courses = create('App\Course', 3);

        $this->withoutExceptionHandling();

        $viewData = $this->get('/courses')->assertViewIs('courses.index')
            ->assertViewHas('courses')
            ->viewData('courses');

        $this->assertCount(3, $viewData);
        $this->assertEquals(
            $courses->sortByDesc('created_at')->first()->toArray(),
            $viewData->first()->toArray()
        ); //first created is at last
    }
}
