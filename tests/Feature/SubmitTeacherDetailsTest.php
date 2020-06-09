<?php

namespace Tests\Feature;

use App\Exceptions\TeacherProfileNotCompleted;
use App\Models\College;
use App\Models\Course;
use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\Programme;
use App\Models\Teacher;
use App\Models\TeachingDetail;
use App\Models\TeachingRecord;
use App\Models\User;
use App\Notifications\AcceptingTeachingRecordsStarted;
use App\Notifications\TeachingRecordsSaved;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubmitTeacherDetailsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function teacher_sends_teaching_details_and_rececives_acknowledgement_notification_via_mail()
    {
        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        $teachingDetail = create(TeachingDetail::class, 1, ['teacher_id' => $teacher->id]);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->signIn($teacher, null);

        $this->withoutExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertRedirect();

        $this->assertCount(1, $records = TeachingRecord::all());
        $submittedRecord = $records->first();

        $teacher->refresh();

        $this->assertEqualsWithDelta(TeachingRecord::getStartDate(), $submittedRecord->valid_from, 1);
        $this->assertEquals($teacher->id, $submittedRecord->teacher_id);
        $this->assertEquals($teacher->college_id, $submittedRecord->college_id);
        $this->assertEquals($teacher->status, $submittedRecord->status);
        $this->assertEquals($teacher->designation, $submittedRecord->designation);
        $this->assertEquals($teachingDetail->programme_id, $submittedRecord->programme_id);
        $this->assertEquals($teachingDetail->course_id, $submittedRecord->course_id);
        $this->assertEquals($teachingDetail->semester, $submittedRecord->semester);
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_college_id_is_null()
    {
        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
            'college_id' => null,
        ]);
        $teachingDetail = create(TeachingDetail::class, 1, ['teacher_id' => $teacher->id]);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->signIn($teacher, null);

        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertRedirect()
            ->assertSessionHasFlash('danger', 'Your profile is not completed. You cannot perform this action.');

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_designation_is_null()
    {
        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
            'designation' => null,
        ]);

        $teachingDetail = create(TeachingDetail::class, 1, ['teacher_id' => $teacher->id]);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->signIn($teacher, null);

        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertRedirect()
            ->assertSessionHasFlash('danger', 'Your profile is not completed. You cannot perform this action.');

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_status_is_null()
    {
        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
            'status' => null,
        ]);

        $teachingDetail = create(TeachingDetail::class, 1, ['teacher_id' => $teacher->id]);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->signIn($teacher, null);

        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertRedirect()
            ->assertSessionHasFlash('danger', 'Your profile is not completed. You cannot perform this action.');

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function details_of_teacher_cannot_be_submited_if_teacher_profile_teaching_details_are_empty()
    {
        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        // NOT adding teaching Details

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->signIn($teacher, null);

        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertRedirect()
            ->assertSessionHasFlash('danger', 'Your profile is not completed. You cannot perform this action.');

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function teachers_cannot_submit_their_profile_if_we_didnt_start_accepting_details()
    {
        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        create(TeachingDetail::class, 1, ['teacher_id' => $teacher->id]);

        // TeachingRecord::startAccepting(now(), now()->addMonths(6));
        // Not accepting records as of now.

        $this->signIn($teacher, null);

        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertForbidden();

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function teachers_cannot_submit_their_profile_if_deadline_has_expired()
    {
        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        create(TeachingDetail::class, 1, ['teacher_id' => $teacher->id]);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        // Time expired. Fast-forward to future (one day after deadline).
        SupportCarbon::setTestNow(now()->addMonths(6)->addDay());

        $this->signIn($teacher, null);

        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertForbidden();

        $this->assertEquals(0, TeachingRecord::count());
    }

    /** @test */
    public function teacher_rececives_acknowledgement_notification_only_if_they_choose_to()
    {
        Notification::fake();

        $teacherPrefersAck = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        $teacherDoesntPreferAck = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        create(TeachingDetail::class, 1, ['teacher_id' => $teacherPrefersAck->id]);
        create(TeachingDetail::class, 1, ['teacher_id' => $teacherDoesntPreferAck->id]);

        TeachingRecord::startAccepting(now(), now()->addMonths(6));

        $this->signIn($teacherPrefersAck, null);
        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'), ['notify' => true])
            ->assertRedirect();
        $this->assertEquals(1, TeachingRecord::count());
        Notification::assertSentTo($teacherPrefersAck, TeachingRecordsSaved::class);

        $this->signIn($teacherDoesntPreferAck, null);
        $this->withExceptionHandling()
            ->post(route('teaching-records.submit'))
            ->assertRedirect();
        $this->assertEquals(2, TeachingRecord::count());
        Notification::assertNotSentTo($teacherDoesntPreferAck, TeachingRecordsSaved::class);
    }
}
