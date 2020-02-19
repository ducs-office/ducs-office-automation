<?php

namespace Tests\Feature;

use App\PastTeachersProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AcceptTeachingRecordsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teachers_details_submission_period_can_be_set()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->post(route('staff.teaching_records.accept'), [
                'start_date' => $start = now(),
                'end_date' => $end = now()->addMonths(6),
            ])
            ->assertRedirect();

        $this->assertEquals($start, PastTeachersProfile::getStartDate());
        $this->assertEquals($end, PastTeachersProfile::getEndDate());
    }

    /** @test */
    public function teachers_details_submission_period_can_be_extended()
    {
        $this->signIn();

        PastTeachersProfile::startAccepting(now(), now()->addMonths(6));
        $this->withoutExceptionHandling()
            ->patch(route('staff.teaching_records.extend'), [
                'extend_to' => $extend = PastTeachersProfile::getEndDate()->addMonths(1),
            ])
            ->assertRedirect();

        $this->assertEquals($extend, PastTeachersProfile::getEndDate());
    }

    /** @test */
    public function extended_date_can_not_be_less_than_end_date()
    {
        $this->signIn();

        PastTeachersProfile::startAccepting(now(), now()->addMonths(6));
        $end = PastTeachersProfile::getEndDate();

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.teaching_records.extend'), [
                    'extend_to' => now()->addMonths(4),
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('extend_to', $e->errors());
        }

        $this->assertEquals($end, PastTeachersProfile::getEndDate());
    }

    /** @test */
    public function end_date_can_not_be_less_than_start_date()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.teaching_records.accept'), [
                    'start_date' => now()->addMonths(2),
                    'end_date' => now(),
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('end_date', $e->errors());
        }

        $this->assertEquals(null, PastTeachersProfile::getStartDate());
        $this->assertEquals(null, PastTeachersProfile::getEndDate());
    }
}
