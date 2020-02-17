<?php

namespace Tests\Feature;

use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_be_deleted()
    {
        $teacher = create(Teacher::class);

        $this->signIn();

        $this->withoutExceptionHandling()
            ->delete(route('staff.teachers.destroy', $teacher))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'College teacher deleted successfully');

        $this->assertNull($teacher->fresh());
    }
}
