<?php

namespace Tests\Feature;

use App\Programme;
use App\Course;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_course()
    {
        $this->withoutExceptionHandling()
            ->signIn(create(User::class));

        $this->post('/courses', $course = [
            'code' => 'MCS-102',
            'name' => 'Design and Analysis of Algorithms',
            'attachments' =>  $attachment = [UploadedFile::fake()->create('document.pdf')]
        ])->assertRedirect('/courses')
        ->assertSessionHasFlash('success', 'Course created successfully!');
           
        $this->assertEquals(1, Course::count());
        
        $this->assertEquals(Course::first()->attachments[0]->path, 'course_attachments/'.$attachment[0]->hashName());
        $this->assertEquals(Course::first()->code, $course['code']);
    }
}
