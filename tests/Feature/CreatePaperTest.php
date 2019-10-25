<?php

namespace Tests\Feature;

use App\Course;
use App\Paper;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreatePaperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_paper_and_assign_it_to_a_course()
    {
        $this->withoutExceptionHandling()
            ->be(create(User::class));

        $course = create(Course::class);

        $this->post('/papers', $params = [
            'code' => 'MCS-102',
            'name' => 'Design and Analysis of Algorithms',
            'course_id' => $course->id,
        ])->assertRedirect('/papers')
        ->assertSessionHasFlash('success', 'Paper created successfully!');

        $this->assertEquals(1, Paper::count());

        tap(Paper::first(), function($paper) use ($params) {
            foreach($params as $param => $value) {
                $this->assertEquals($value, $paper->{$param});
            }
        });

    }
}
