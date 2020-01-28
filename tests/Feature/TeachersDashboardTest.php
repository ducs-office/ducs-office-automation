<?php

namespace Tests\Feature;

use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeachersDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_visit_dashboard()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $response = $this->withoutExceptionHandling()
            ->get('/teachers/profile')
            ->assertSuccessful()
            ->assertSee($teacher->name);
    }
}
