<?php

namespace Tests\Feature;

use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupervisorManagesScholarCourseworkTest extends TestCase
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
            ->post(route('research.scholars.courseworks.store', $scholar), [
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
            ->post(route('research.scholars.courseworks.store', $scholar), [
                'course_ids' => $courses->pluck('id')->toArray(),
            ])->assertRedirect();

        $this->assertCount(2, $scholar->fresh()->courseworks);
        $this->assertEquals($courses->pluck('id'), $scholar->fresh()->courseworks->pluck('id'));
    }

    /** @test */
    public function supervisors_can_add_elective_courses_to_only_the_scholars_whom_they_supervise()
    {
        $profNeelima = create(User::class, 1, ['name' => 'Prof. Neelima Gupta']);
        $profPoonam = create(User::class, 1, ['name' => 'Prof. Poonam Bedi']);

        $profNeelimaProfile = $profNeelima->supervisorProfile()->create();
        $profPoonamProfile = $profPoonam->supervisorProfile()->create();

        $rajni = create(Scholar::class, 1, [
            'supervisor_profile_id' => $profNeelimaProfile->id,
        ]);
        $pushkar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $profPoonamProfile->id,
        ]);

        $electiveCourses = create(PhdCourse::class, 2, ['type' => 'E']);

        $this->signIn($profPoonam);

        $this->withExceptionHandling()
            ->post(route('research.scholars.courseworks.store', $rajni), [
                'course_ids' => $electiveCourses->pluck('id')->toArray(),
            ])->assertForbidden();

        $this->assertCount(0, $rajni->fresh()->courseworks);
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
            ->patch(route('research.scholars.courseworks.complete', [$scholar, $course->id]))
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
            ->patch(route('research.scholars.courseworks.complete', [$scholar, $course->id]))
            ->assertRedirect();

        $pivot = $scholar->courseworks()->firstOrFail()->pivot;

        $this->assertEqualsWithDelta(now(), $pivot->completed_at, 1);
    }

    /** @test */
    public function supervisors_can_mark_courses_completed_for_only_the_scholars_whom_they_supervise()
    {
        $profNeelima = create(User::class, 1, ['name' => 'Prof. Neelima Gupta']);
        $profPoonam = create(User::class, 1, ['name' => 'Prof. Poonam Bedi']);

        $profNeelimaProfile = $profNeelima->supervisorProfile()->create();
        $profPoonamProfile = $profPoonam->supervisorProfile()->create();

        $coreCourse = create(PhdCourse::class, 1, ['type' => 'C']);

        $rajni = create(Scholar::class, 1, [
            'supervisor_profile_id' => $profNeelimaProfile->id,
        ]);
        $pushkar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $profPoonamProfile->id,
        ]);

        $this->signIn($profPoonam);

        $this->withExceptionHandling()
            ->patch(route('research.scholars.courseworks.complete', [$rajni, $coreCourse]))
            ->assertForbidden();

        $this->assertNull(
            $rajni->fresh()->courseworks->first()->pivot->completed_at,
            'Course was marked completed by another supervisor'
        );
    }
}
