<?php

namespace Tests\Feature;

use App\Course;
use App\Paper;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ViewPapersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_papers_ordered_by_latest_added()
    {
        $this->signIn();

        $papers = create(Paper::class, 3);

        $viewPapers = $this->withoutExceptionHandling()->get('/papers')
            ->assertSuccessful()
            ->assertViewIs('papers.index')
            ->assertViewHas('papers')
            ->viewData('papers');

        $this->assertCount(3, $viewPapers);
        $this->assertSame(
            $papers->sortByDesc('created_at')->pluck('id')->toArray(),
            $viewPapers->pluck('id')->toArray()
        );
    }

    /** @test */
    public function papers_index_page_also_has_courses()
    {
        $this->signIn();

        $courses = create(Course::class, 3);

        $viewCourses = $this->withoutExceptionHandling()->get('/papers')
            ->assertSuccessful()
            ->assertViewHas('courses')
            ->viewData('courses');

        $this->assertInstanceOf(Collection::class, $viewCourses);
        $this->assertCount(3, $viewCourses);
        $this->assertSame($viewCourses->toArray(), $courses->pluck('name', 'id')->toArray());
    }
}
