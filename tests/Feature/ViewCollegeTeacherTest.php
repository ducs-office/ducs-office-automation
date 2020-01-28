<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\CollegeTeacher;

class ViewCollegeTeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_view_college_teachers()
    {
        create(CollegeTeacher::class, 4);

        $this->withExceptionHandling()
            ->get('/college-teachers')
            ->assertRedirect('/login');
    }

    /** @test */
    public function college_teachers_can_be_viewed()
    {
        $this->signIn();

        create(CollegeTeacher::class, 4);

        $viewData = $this->withoutExceptionHandling()
            ->get('/college-teachers')
            ->assertViewIs('staff.college_teachers.index')
            ->assertViewHas('collegeTeachers')
            ->viewData('collegeTeachers');

        $this->assertEquals(CollegeTeacher::count(), count($viewData));
    }
}
