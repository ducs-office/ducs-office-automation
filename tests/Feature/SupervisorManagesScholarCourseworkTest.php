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

        $course = create(PhdCourse::class, 1, ['type' => PrePhdCourseType::ELECTIVE]);

        $this->withoutExceptionHandling()
            ->post(route('scholars.courseworks.store', $scholar), [
                'course_id' => $course->id,
            ])->assertRedirect();

        $this->assertCount(1, $scholar->fresh()->courseworks);
        $this->assertEquals($course->id, $scholar->fresh()->courseworks->first()->id);
    }

    /** @test */
    public function faculty_supervisors_can_add_elective_courses_to_scholars_courseworks()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);

        $this->signIn($supervisor);

        $course = create(PhdCourse::class, 1, ['type' => PrePhdCourseType::ELECTIVE]);

        $this->withoutExceptionHandling()
            ->post(route('scholars.courseworks.store', $scholar), [
                'course_id' => $course->id,
            ])->assertRedirect();

        $this->assertCount(1, $scholar->fresh()->courseworks);
        $this->assertEquals($course->id, $scholar->fresh()->courseworks->first()->id);
    }

    /** @test */
    public function supervisors_can_add_elective_courses_to_only_those_scholars_whom_they_supervise()
    {
        $profNeelima = factory(User::class)->states('supervisor')->create(['name' => 'Prof. Neelima Gupta']);
        $profPoonam = factory(User::class)->states('supervisor')->create(['name' => 'Prof. Poonam Bedi']);

        $rajni = create(Scholar::class);
        $rajni->supervisors()->attach($profNeelima);

        $pushkar = create(Scholar::class);
        $pushkar->supervisors()->attach($profPoonam);

        $electiveCourse = create(PhdCourse::class, 1, ['type' => PrePhdCourseType::ELECTIVE]);

        $this->signIn($profPoonam);

        $this->withExceptionHandling()
            ->post(route('scholars.courseworks.store', $rajni), [
                'course_id' => $electiveCourse->id,
            ])->assertForbidden();

        $this->assertCount(0, $rajni->fresh()->courseworks);
    }

    /** @test */
    public function supervisors_can_view_marksheets_of_only_those_scholars_whom_they_supervise()
    {
        Storage::fake();

        $profNeelima = factory(User::class)->states('supervisor')->create(['name' => 'Prof. Neelima Gupta']);

        $rajni = create(Scholar::class);
        $rajni->supervisors()->attach($profNeelima);

        $this->signIn($profNeelima);

        $course = create(PhdCourse::class);

        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $rajni->courseworks()->attach($course, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => now()->format('Y-m-d'),
        ]);

        $courseCompleted = $rajni->courseworks[0];

        $this->withExceptionHandling()
            ->get(route('scholars.courseworks.marksheet', [$rajni, $courseCompleted->pivot]))
            ->assertSuccessful();
    }

    /** @test */
    public function supervisors_can_not_view_marksheets_of_scholars_whom_they_do_not_supervise()
    {
        Storage::fake();

        $profNeelima = factory(User::class)->states('supervisor')->create(['name' => 'Prof. Neelima Gupta']);
        $profPoonam = factory(User::class)->states('supervisor')->create(['name' => 'Prof. Poonam Bedi']);

        $rajni = create(Scholar::class);
        $rajni->supervisors()->attach($profNeelima);

        $this->signIn($profPoonam);

        $course = create(PhdCourse::class);

        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $rajni->courseworks()->attach($course, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => now()->format('Y-m-d'),
        ]);

        $courseCompleted = $rajni->courseworks[0];

        $this->withExceptionHandling()
            ->get(route('scholars.courseworks.marksheet', [$rajni, $courseCompleted->pivot]))
            ->assertForbidden();
    }
}
