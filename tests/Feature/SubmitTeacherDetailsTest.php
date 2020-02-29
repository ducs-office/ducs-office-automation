<?php

namespace Tests\Feature;

use App\College;
use App\Course;
use App\CourseProgrammeRevision;
use App\Exceptions\TeacherProfileNotCompleted;
use App\Notifications\AcceptingTeachingRecordsStarted;
use App\Notifications\TeachingRecordsSaved;
use App\Programme;
use App\Teacher;
use App\TeachingRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubmitTeacherDetailsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function fillTeacherProfileFormFields($overrides = [])
    {
        return $this->mergeFormFields([
            'phone_no' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'designation' => $this->faker->randomElement(array_keys(config('options.teachers.designations'))),
            'college_id' => function () {
                return factory(College::class)->create()->id;
            },
            'teacher_id' => function () {
                return factory(Teacher::class)->create()->id;
            },
            'teaching_details' => function () {
                return [
                    create(CourseProgrammeRevision::class)->only([
                        'programme_revision_id', 'course_id', 'semester',
                    ]),
                ];
            },
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

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->withoutExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertSessionHasFlash('success', 'Details submitted successfully!');

        $this->assertEquals(1, TeachingRecord::count());
        $this->assertEqualsWithDelta(TeachingRecord::getStartDate(), TeachingRecord::first()->valid_from, 1);
        $this->assertEquals($teacher->profile->fresh()->teacher_id, TeachingRecord::first()->teacher_id);
        $this->assertEquals($teacher->profile->fresh()->college_id, TeachingRecord::first()->college_id);
        $this->assertEquals($teacher->profile->fresh()->designation, TeachingRecord::first()->designation);
        $this->assertEquals($teacher->profile->fresh()->teachingDetails->first()->programme_id, TeachingRecord::first()->programme_id);
        $this->assertEquals($teacher->profile->fresh()->teachingDetails->first()->course_id, TeachingRecord::first()->course_id);
        $this->assertEquals($teacher->profile->fresh()->teachingDetails->first()->semester, TeachingRecord::first()->semester);
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_college_id_is_null()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id, 'college_id' => '']);

        $this->patch(route('teachers.profile.update'), $profile_form);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->withExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertRedirect()
            ->assertSessionHasFlash('danger', 'Your profile is not completed. You cannot perform this action.');

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_designation_is_null()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id, 'designation' => '']);

        $this->patch(route('teachers.profile.update'), $profile_form);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->withExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertRedirect()
            ->assertSessionHasFlash('danger', 'Your profile is not completed. You cannot perform this action.');

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_teaching_details_are_empty()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id, 'teaching_details' => '']);

        $this->patch(route('teachers.profile.update'), $profile_form);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        try {
            $this->withoutExceptionHandling()
                ->from('/teachers')
                ->post(route('teachers.profile.submit'))
                ->assertRedirect('/teachers')
                ->assertSessionHasFlash('danger', 'Your profile is not completed. You cannot perform this action.');

            $this->fail('Profile Not Completed Exception was not thrown');
        } catch (TeacherProfileNotCompleted $e) {
            $this->assertEquals(0, TeachingRecord::count());
        }
    }

    /** @test */
    public function teachers_cannot_submit_their_profile_if_accept_details_date_is_not_set_or_time_period_expired()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields([
            'teacher_id' => $teacher->id,
        ]);

        $this->patch(route('teachers.profile.update'), $profile_form);

        // TeachingRecord::startAccepting(now(), now()->addMonths(6));
        // submit without any start_date
        $this->withExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertForbidden();

        $this->assertEquals(0, TeachingRecord::count());

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        \Carbon\Carbon::setTestNow(now()->addMonths(1));

        // submit after start_date is set
        $this->withoutExceptionHandling()
            ->from('/teachers')
            ->post(route('teachers.profile.submit'))
            ->assertRedirect('/teachers')
            ->assertSessionHasNoErrors();

        $this->assertEquals(1, TeachingRecord::count());

        // submitting again
        $this->withExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertForbidden();

        $this->assertEquals(1, TeachingRecord::count());
    }

    /** @test */
    public function notification_is_sent_to_all_teachers_when_accepting_details_has_started()
    {
        $this->signIn();

        Notification::fake();

        $teachers = create(Teacher::class, 5);

        $this->withExceptionHandling()
            ->post(route('staff.teaching_records.accept'), [
                'start_date' => $start_date = now(),
                'end_date' => $end_date = now()->addMonths(6),
            ]);

        Notification::assertSentTo(
            $teachers,
            AcceptingTeachingRecordsStarted::class,
            function ($notification) use ($start_date, $end_date) {
                return $notification->start_date == $start_date
                        && $notification->end_date == $end_date;
            }
        );
    }

    /** @test */
    public function acknowledgement_is_sent_via_mail_when_teacher_submitted_their_profile()
    {
        Notification::fake();

        $this->signInTeacher($teacher = create(Teacher::class));

        $profile_form = $this->fillTeacherProfileFormFields(['teacher_id' => $teacher->id]);

        $this->patch(route('teachers.profile.update'), $profile_form);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->withoutExceptionHandling()
            ->post(route('teachers.profile.submit'))
            ->assertSessionHasFlash('success', 'Details submitted successfully!');

        Notification::assertSentTo($teacher, TeachingRecordsSaved::class);
    }
}
