<?php

namespace Tests\Feature;

use App\Course;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCourseTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function admin_can_create_new_course()
    {
        $this->be(factory(User::class)->create());

        $this->post('/courses', [
            'code' => 'MCS',
            'name' => 'M.Sc. Computer Science',
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course created successfully');
        
        $this->assertEquals(1, Course::count());
    }
}
