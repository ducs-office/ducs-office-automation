<?php

namespace Tests\Feature;

use App\CollegeTeacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollegeTeachersDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function college_teacher_can_visit_dashboard()
    {
        $this->signInCollegeTeacher($teacher = create(CollegeTeacher::class));

        $response = $this->withoutExceptionHandling()
            ->get('/college_teachers')
            ->assertSuccessful()
            ->assertSee($teacher->name);
    }
}
