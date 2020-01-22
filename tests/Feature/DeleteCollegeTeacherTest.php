<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\CollegeTeacher;

class DeleteCollegeTeacherTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function college_teacher_can_be_deleted()
    {
        $collegeTeacher = create(CollegeTeacher::class);

        $this->signIn();

        $this->withoutExceptionHandling()
            ->from('/college-teachers')
            ->delete('/college-teachers/'.$collegeTeacher->id)
            ->assertRedirect('/college-teachers')
            ->assertSessionHasFlash('success', 'College teacher deleted successfully');

        $this->assertNull($collegeTeacher->fresh());
    }
}
