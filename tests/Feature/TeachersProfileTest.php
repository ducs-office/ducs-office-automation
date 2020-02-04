<?php

namespace Tests\Feature;

use App\Teacher;
use App\TeacherProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeachersProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_view_their_profiles()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $response = $this->withoutExceptionHandling()
            ->get(route('teachers.profile'))
            ->assertSuccessful()
            ->assertSee($teacher->name);
    }
}
