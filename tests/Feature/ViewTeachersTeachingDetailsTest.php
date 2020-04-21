<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewTeachersTeachingDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teachers_all_details_can_be_viewed()
    {
        $teacher = create(Teacher::class);
        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->get(route('staff.teachers.show', $teacher))
            ->assertSuccessful();
    }
}
