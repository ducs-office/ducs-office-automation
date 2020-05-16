<?php

namespace Tests\Feature;

use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use App\Types\PrePhdCourseType;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupervisorManagesScholarCourseworkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_supervisors_can_add_elective_courses_to_scholars_courseworks()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);

        $this->signIn($supervisor);

        $courses = create(PhdCourse::class, 2, ['type' => PrePhdCourseType::ELECTIVE]);

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
        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);

        $this->signIn($supervisor);

        $courses = create(PhdCourse::class, 2, ['type' => PrePhdCourseType::ELECTIVE]);

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
        $profNeelima = factory(User::class)->states('supervisor')->create(['name' => 'Prof. Neelima Gupta']);
        $profPoonam = factory(User::class)->states('supervisor')->create(['name' => 'Prof. Poonam Bedi']);

        $rajni = create(Scholar::class);
        $rajni->supervisors()->attach($profNeelima);

        $pushkar = create(Scholar::class);
        $pushkar->supervisors()->attach($profPoonam);

        $electiveCourses = create(PhdCourse::class, 2, ['type' => PrePhdCourseType::ELECTIVE]);

        $this->signIn($profPoonam);

        $this->withExceptionHandling()
            ->post(route('research.scholars.courseworks.store', $rajni), [
                'course_ids' => $electiveCourses->pluck('id')->toArray(),
            ])->assertForbidden();

        $this->assertCount(0, $rajni->fresh()->courseworks);
    }
}
