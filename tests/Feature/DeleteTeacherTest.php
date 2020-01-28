<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Teacher;

class DeleteTeacherTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function teacher_can_be_deleted()
    {
        $Teacher = create(Teacher::class);

        $this->signIn();

        $this->withoutExceptionHandling()
            ->from('/teachers')
            ->delete('/teachers/'.$Teacher->id)
            ->assertRedirect('/teachers')
            ->assertSessionHasFlash('success', 'College teacher deleted successfully');

        $this->assertNull($Teacher->fresh());
    }
}
