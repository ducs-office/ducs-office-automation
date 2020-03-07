<?php

namespace Tests\Feature;

use App\Course;
use App\PhdCourse;
use App\Programme;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ViewPhdCoursesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_phd_courses_ordered_by_latest_added()
    {
        $this->signIn();

        $courses = create(PhdCourse::class, 3);

        $viewCourses = $this->withoutExceptionHandling()
            ->get(route('staff.phd_courses.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.phd_courses.index')
            ->assertViewHas('courses')
            ->viewData('courses');

        $this->assertCount(3, $viewCourses);
        $this->assertSame(
            $courses->sortByDesc('created_at')->pluck('id')->toArray(),
            $viewCourses->pluck('id')->toArray()
        );
    }
}
