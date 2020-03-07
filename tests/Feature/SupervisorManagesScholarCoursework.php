<?php

namespace Tests\Feature;

use App\PhdCourse;
use App\Scholar;
use App\SupervisorProfile;
use App\Teacher;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupervisorManagesScholarCoursework extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_supervisors_can_add_elective_courses_to_scholars_courseworks()
    {
        $teacher = create(Teacher::class);
        $supervisorProfile = $teacher->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);

        $this->signInTeacher($teacher);

        $courses = create(PhdCourse::class, 2, ['type' => 'E']);

        $this->withoutExceptionHandling()
            ->post(route('teachers.scholars.courseworks.store', $scholar), [
                'course_ids' => $courses->pluck('id')->toArray(),
            ])->assertRedirect();

        $this->assertCount(2, $scholar->fresh()->courseworks);
        $this->assertEquals($courses->pluck('id'), $scholar->fresh()->courseworks->pluck('id'));
    }

    /** @test */
    public function faculty_supervisors_can_add_elective_courses_to_scholars_courseworks()
    {
        $user = create(User::class);
        $supervisorProfile = $user->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);

        $this->signIn($user);

        $courses = create(PhdCourse::class, 2, ['type' => 'E']);

        $this->withoutExceptionHandling()
            ->post(route('staff.scholars.courseworks.store', $scholar), [
                'course_ids' => $courses->pluck('id')->toArray(),
            ])->assertRedirect();

        $this->assertCount(2, $scholar->fresh()->courseworks);
        $this->assertEquals($courses->pluck('id'), $scholar->fresh()->courseworks->pluck('id'));
    }

    /** @test */
    public function faculty_supervisors_can_mark_scholars_coursework_as_completed()
    {
        $user = create(User::class);
        $supervisorProfile = $user->supervisorProfile()->create();

        $course = create(PhdCourse::class, 1, ['type' => 'E']);
        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);
        $scholar->courseworks()->attach($course->id);

        $this->signIn($user);

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.courseworks.complete', [$scholar, $course->id]))
            ->assertRedirect();

        $pivot = $scholar->courseworks()->firstOrFail()->pivot;

        $this->assertEqualsWithDelta(now(), $pivot->completed_at, 1);
    }

    /** @test */
    public function teacher_supervisors_can_mark_scholars_coursework_as_completed()
    {
        $teacher = create(Teacher::class);
        $supervisorProfile = $teacher->supervisorProfile()->create();

        $course = create(PhdCourse::class, 1, ['type' => 'E']);
        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);
        $scholar->courseworks()->attach($course->id);

        $this->signInTeacher($teacher);

        $this->withoutExceptionHandling()
            ->patch(route('teachers.scholars.courseworks.complete', [$scholar, $course->id]))
            ->assertRedirect();

        $pivot = $scholar->courseworks()->firstOrFail()->pivot;

        $this->assertEqualsWithDelta(now(), $pivot->completed_at, 1);
    }
}
