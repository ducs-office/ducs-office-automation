<?php

namespace Tests\Feature;

use App\PastTeachersProfile;
use App\TeacherProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Teacher;
use App\Course;
use App\Programme;
use App\College;
use Illuminate\Validation\ValidationException;

class SubmitTeacherDetailsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function fillTeacherProfileFormFields($overrides = [])
    {
        return $this->mergeFormFields([
        'phone_no' => $this->faker->phoneNumber,
        'address' => $this->faker->address,
        'designation' => $this->faker->randomElement(array_keys(config('options.teachers.designation'))),
        'ifsc' => $this->faker->text(12),
        'account_no' => $this->faker->bankAccountNumber,
        'bank_name' => $this->faker->name,
        'bank_branch' => $this->faker->address,
        'college_id' => function () {
            return factory(College::class)->create()->id;
        },
        'teacher_id' => function () {
            return factory(Teacher::class)->create()->id;
        },
        'teaching_details' => function () {
            $programme = create(Programme::class, 1, ['wef' => now()]);
            $course = create(Course::class);
            $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);
            $revision->courses()->attach($course, ['semester' => 1]);
            return [
                ['programme' => $programme->id, 'course' => $course->id]
            ];
        }

    ], $overrides);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function details_of_teacher_can_be_submitted()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id]);
      
        $this->patch(route('teachers.profile.update'), $profile_form);
       
        $this->withoutExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertSessionHasFlash('success', 'Details submitted successfully!');

        $this->assertEquals(1, PastTeachersProfile::count());
        $this->assertEquals($teacher->profile->fresh()->college_id, PastTeachersProfile::first()->college_id);
        $this->assertEquals($teacher->profile->fresh()->designation, PastTeachersProfile::first()->designation);
        $this->assertEquals($teacher->profile->fresh()->teaching_details->count(), PastTeachersProfile::first()->past_teaching_details->count());
        $this->assertEquals($teacher->profile->fresh()->teaching_details->first()->id, PastTeachersProfile::first()->past_teaching_details->first()->course_programme_revision_id);
        $this->assertEquals($teacher->profile->fresh()->teacher_id, PastTeachersProfile::first()->teacher_id);
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_college_id_is_null()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id, 'college_id' =>'']);

        $this->patch(route('teachers.profile.update'), $profile_form);

        $this->withoutExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertSessionHasFlash('fail', 'Fill complete details to make submission');

        $this->assertEquals(0, PastTeachersProfile::count());
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_designation_is_null()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id, 'designation' =>'']);

        $this->patch(route('teachers.profile.update'), $profile_form);

        $this->withoutExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertSessionHasFlash('fail', 'Fill complete details to make submission');

        $this->assertEquals(0, PastTeachersProfile::count());
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_teaching_details_is_null()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id, 'teaching_details' =>'']);

        $this->patch(route('teachers.profile.update'), $profile_form);

        $this->withoutExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertSessionHasFlash('fail', 'Fill complete details to make submission');

        $this->assertEquals(0, PastTeachersProfile::count());
    }
}
