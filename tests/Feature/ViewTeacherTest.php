<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Teacher;

class ViewTeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_view_teachers()
    {
        create(Teacher::class, 4);

        $this->withExceptionHandling()
            ->get(route('staff.teachers.index'))
            ->assertRedirect();
    }

    /** @test */
    public function teachers_can_be_viewed()
    {
        $this->signIn();

        create(Teacher::class, 4);

        $viewData = $this->withoutExceptionHandling()
            ->get(route('staff.teachers.index'))
            ->assertViewIs('staff.teachers.index')
            ->assertViewHas('Teachers')
            ->viewData('Teachers');

        $this->assertEquals(Teacher::count(), count($viewData));
    }
}
