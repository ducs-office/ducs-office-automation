<?php

namespace Tests\Feature;

use App\PhdCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdatePhdCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_course_code()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(PhdCourse::class);

        $response = $this->patch(route('staff.phd_courses.update', $course), [
            'code' => $newCode = 'New123',
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, PhdCourse::count());
        $this->assertEquals($newCode, $course->fresh()->code);
    }

    /** @test */
    public function admin_can_update_course_name()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(PhdCourse::class);

        $response = $this->patch(route('staff.phd_courses.update', $course), [
            'name' => $newName = 'New course',
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, PhdCourse::count());
        $this->assertEquals($newName, $course->fresh()->name);
    }

    /** @test */
    public function course_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(PhdCourse::class);

        $response = $this->patch(route('staff.phd_courses.update', $course), [
            'code' => $course->code,
            'name' => $newName = 'New course',
        ])->assertRedirect()
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, PhdCourse::count());
        $this->assertEquals($newName, $course->fresh()->name);
    }

    /** @test */
    public function admin_can_update_type_of_course()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $course = create(PhdCourse::class, 1, ['type' => 'C']);

        $this->patch(route('staff.phd_courses.update', $course), [
            'type' => $newType = 'E',
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Course updated successfully!');

        $this->assertEquals(1, PhdCourse::count());
        $this->assertEquals($newType, $course->fresh()->type);
    }
}
