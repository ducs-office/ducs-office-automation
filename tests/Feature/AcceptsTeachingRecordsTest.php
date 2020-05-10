<?php

namespace Tests\Feature;

use App\Models\TeachingRecord;
use App\Models\User;
use App\Notifications\AcceptingTeachingRecordsStarted;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AcceptsTeachingRecordsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function notification_is_sent_to_all_teachers_when_accepting_details_has_started()
    {
        $this->signIn();

        Notification::fake();

        $faculty = create(User::class, 3, [
            'category' => UserCategory::FACULTY_TEACHER,
        ]);
        $teachers = create(User::class, 5, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('teaching-records.start'), [
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

        Notification::assertNotSentTo($faculty, AcceptingTeachingRecordsStarted::class);
    }

    /** @test */
    public function teaching_records_submission_period_can_be_set()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->post(route('teaching-records.start'), [
                'start_date' => $start = now(),
                'end_date' => $end = now()->addMonths(6),
            ])
            ->assertRedirect();

        $this->assertEquals($start, TeachingRecord::getStartDate());
        $this->assertEquals($end, TeachingRecord::getEndDate());
    }

    /** @test */
    public function teaching_records_submission_period_can_be_extended()
    {
        $this->signIn();

        TeachingRecord::startAccepting(now(), now()->addMonths(6));
        $this->withoutExceptionHandling()
            ->patch(route('teaching-records.extend'), [
                'extend_to' => $extend = TeachingRecord::getEndDate()->addMonths(1),
            ])
            ->assertRedirect();

        $this->assertEquals($extend, TeachingRecord::getEndDate());
    }

    /** @test */
    public function teaching_records_submission_extended_date_can_not_be_less_than_end_date()
    {
        $this->signIn();

        TeachingRecord::startAccepting(now(), now()->addMonths(6));
        $end = TeachingRecord::getEndDate();

        try {
            $this->withoutExceptionHandling()
                ->patch(route('teaching-records.extend'), [
                    'extend_to' => now()->addMonths(4),
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('extend_to', $e->errors());
        }

        $this->assertEquals($end, TeachingRecord::getEndDate());
    }

    /** @test */
    public function teaching_records_submission_end_date_can_not_be_less_than_start_date()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post(route('teaching-records.start'), [
                    'start_date' => now()->addMonths(2),
                    'end_date' => now(),
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('end_date', $e->errors());
        }

        $this->assertEquals(null, TeachingRecord::getStartDate());
        $this->assertEquals(null, TeachingRecord::getEndDate());
    }
}
