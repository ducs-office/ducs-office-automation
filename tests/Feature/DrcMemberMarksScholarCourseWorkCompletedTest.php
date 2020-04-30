<?php

namespace Tests\Feature;

use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DrcMemberMarksScholarCourseWorkCompletedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_mark_scholar_course_work_completed_only_if_they_have_permission()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->givePermissionTo('phd course work:mark completed');

        $course = create(PhdCourse::class);

        $scholar = create(Scholar::class);

        $scholar->courseworks()->attach($course->id);

        $marksheet = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf');

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.courseworks.complete', [$scholar, $course->id]), [
                'marksheet' => $marksheet,
                'completed_on' => $completedOn = now()->format('Y-m-d'),
            ])->assertRedirect();

        $pivot = $scholar->courseworks()->firstOrFail()->pivot;

        $this->assertEquals($completedOn, $pivot->completed_on->format('Y-m-d'));
        $this->assertEquals($marksheet->hashName('scholar_marksheets'), $pivot->marksheet_path);
    }

    /** @test */
    public function user_can_not_mark_scholar_course_work_completed_if_they_do_not_have_permission()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('phd course work:mark completed');

        $course = create(PhdCourse::class);

        $scholar = create(Scholar::class);

        $scholar->courseworks()->attach($course->id);

        $marksheet = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf');

        $this->withExceptionHandling()
            ->patch(route('research.scholars.courseworks.complete', [$scholar, $course->id]), [
                'marksheet' => $marksheet,
                'completed_on' => $completedOn = now()->format('Y-m-d'),
            ])->assertForbidden();

        $pivot = $scholar->courseworks()->firstOrFail()->pivot;

        $this->assertEquals(null, $pivot->completed_on);
        $this->assertEquals(null, $pivot->marksheet_path);
    }

    /** @test */
    public function marksheet_upload_is_required_to_mark_a_scholar_course_work_completed()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->givePermissionTo('phd course work:mark completed');

        $course = create(PhdCourse::class);

        $scholar = create(Scholar::class);

        $scholar->courseworks()->attach($course->id);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('research.scholars.courseworks.complete', [$scholar, $course->id]), [
                    'completed_on' => now()->format('Y-m-d'),
                ]);

            $this->fail('Marksheet required was not validated');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('marksheet', $e->errors());
        }

        $pivot = $scholar->courseworks()->firstOrFail()->pivot;

        $this->assertNull($pivot->completed_at, 'Course work was marked completed without marksheet upload');
    }

    /** @test */
    public function completed_on_is_required_to_mark_a_scholar_course_work_completed()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->givePermissionTo('phd course work:mark completed');

        $course = create(PhdCourse::class);

        $scholar = create(Scholar::class);

        $scholar->courseworks()->attach($course->id);

        $marksheet = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf');

        try {
            $this->withoutExceptionHandling()
                ->patch(route('research.scholars.courseworks.complete', [$scholar, $course->id]), [
                    'marksheet' => $marksheet,
                ]);

            $this->fail('Completed On was not validated');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('completed_on', $e->errors());
        }

        $pivot = $scholar->courseworks()->firstOrFail()->pivot;

        $this->assertNull($pivot->completed_at, 'Course work was marked completed without mentioning date of completion');
    }

    /** @test */
    public function user_can_view_scholar_course_work_marksheets_only_if_they_have_permission_to_view_scholar()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->givePermissionTo('scholars:view');

        $course = create(PhdCourse::class);

        $scholar = create(Scholar::class);

        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $scholar->courseworks()->attach($course, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => now()->format('Y-m-d'),
        ]);

        $courseCompleted = $scholar->courseworks[0];

        $this->withoutExceptionHandling()
            ->get(route('research.scholars.courseworks.marksheet', [$scholar, $courseCompleted->pivot]))
            ->assertSuccessful();
    }

    /** @test */
    public function user_can_not_view_scholar_course_work_marksheets_if_they_do_not_have_permission_to_view_scholar()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->roles->every->revokePermissionTo('scholars:view');

        $course = create(PhdCourse::class);

        $scholar = create(Scholar::class);

        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $scholar->courseworks()->attach($course, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => now()->format('Y-m-d'),
        ]);

        $courseCompleted = $scholar->courseworks[0];

        $this->withExceptionHandling()
            ->get(route('research.scholars.courseworks.marksheet', [$scholar, $courseCompleted->pivot]))
            ->assertForbidden();
    }
}
