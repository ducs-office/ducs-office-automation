<?php

namespace Tests\Feature;

use App\Course;
use App\Programme;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ViewCoursesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_courses_ordered_by_latest_added()
    {
        $this->signIn();

        $courses = create(Course::class, 3);

        $viewCourses = $this->withoutExceptionHandling()->get(route('staff.courses.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.courses.index')
            ->assertViewHas('courses')
            ->viewData('courses');

        $this->assertCount(3, $viewCourses);
        $this->assertSame(
            $courses->sortByDesc('created_at')->pluck('id')->toArray(),
            $viewCourses->pluck('id')->toArray()
        );
    }
}
