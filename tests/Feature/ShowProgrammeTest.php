<?php

namespace Tests\Feature;

use App\Programme;
use App\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_a_single_programme()
    {
        $this->signIn();

        $programme = create(Programme::class);

        $this->withoutExceptionHandling()
            ->get("/programmes/$programme->id")
            ->assertSuccessful()
            ->assertViewIs('programmes.show')
            ->assertViewHasAll(['programme','programmeAllVersionCourses']);
    }

    /** @test */
    public function show_has_all_versions_of_the_programme()
    {
        $this->signIn();

        $programme = create(Programme::class);
        $courses = create(Course::class, 2);

        $programme->courses()->attach($courses[0], ['semester' => 1, 'revised_on' => now()->format('y-m-d')]);
        $programme->courses()->attach($courses[1], ['semester' => 1, 'revised_on' => now()->addYear()->format('y-m-d')]);

        $programmeAllVersionCourses = $this->withoutExceptionHandling()
            ->get("/programmes/$programme->id")
            ->assertSuccessful()
            ->assertViewIs('programmes.show')
            ->assertViewHasAll(['programme','programmeAllVersionCourses'])
            ->viewData('programmeAllVersionCourses');

        $this->assertEquals(2, $programmeAllVersionCourses->count());
    }
}
