<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreatePhdCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_creates_phd_course()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->post(route('staff.phd_courses.store'), $course = [
                'code' => 'RSC001',
                'name' => 'Research Methodology',
                'type' => 'C', // Core
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'PhD Course added successfully!');

        $this->assertDatabaseHas('phd_courses', $course);
    }

    /** @test */
    public function staff_without_create_courses_permission_cannot_create_phd_course()
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->revokePermissionTo('courses:create');
        $this->signIn($user = create(User::class), 'admin');

        $this->withExceptionHandling()
            ->post(route('staff.phd_courses.store'), $course = [
                'code' => 'RSC001',
                'name' => 'Research Methodology',
                'type' => 'C', // Core
            ])->assertForbidden();

        $this->assertDatabaseMissing('phd_courses', $course);
    }
}
