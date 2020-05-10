<?php

namespace Tests\Feature;

use App\Models\TeachingDetail;
use App\Models\TeachingRecord;
use App\Models\User;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use NunoMaduro\LaravelMojito\InteractsWithViews;
use Tests\TestCase;

class ViewUserProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker, InteractsWithViews;

    /** @test */
    public function users_can_view_their_own_profiles()
    {
        $this->signIn($teacher = create(User::class));

        $this->withoutExceptionHandling()
            ->get(route('profiles.show', $teacher))
            ->assertSuccessful()
            ->assertViewHas('user')
            ->assertSee($teacher->name);
    }

    /** @test */
    public function users_can_also_view_others_profiles()
    {
        $otherUser = create(User::class);

        $this->signIn(create(User::class));

        $viewUser = $this->withoutExceptionHandling()
            ->get(route('profiles.show', $otherUser))
            ->assertSuccessful()
            ->assertViewHas('user')
            ->assertSee($otherUser->name)
            ->viewData('user');

        $this->assertTrue($viewUser->is($otherUser));
    }

    /** @test */
    public function college_teacher_can_view_teaching_details_on_their_profile()
    {
        $teacher = create(User::class, 1, ['category' => UserCategory::COLLEGE_TEACHER]);
        $teachingDetails = create(TeachingDetail::class, 3, [
            'teacher_id' => $teacher->id,
        ]);

        $this->signIn($teacher);

        $response = $this->withoutExceptionHandling()
            ->get(route('profiles.show', $teacher))
            ->assertSuccessful()
            ->assertViewHas('user');

        $viewUser = $response->viewData('user');
        $this->assertCount(3, $viewUser->teachingDetails);

        $response->assertSee($teachingDetails[0]->course->name)
            ->assertSee($teachingDetails[1]->course->name)
            ->assertSee($teachingDetails[2]->course->name);
    }

    /** @test */
    public function teaching_records_of_a_teacher_are_passed_in_reverse_chronological_order()
    {
        $this->signIn($teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]));

        $oldRecord = create(TeachingRecord::class, 1, ['teacher_id' => $teacher->id, 'valid_from' => now()->subYear()]);
        $newRecord = create(TeachingRecord::class, 1, ['teacher_id' => $teacher->id, 'valid_from' => now()]);
        $midRecord = create(TeachingRecord::class, 1, ['teacher_id' => $teacher->id, 'valid_from' => now()->subMonths(6)]);

        $viewUser = $this->withoutExceptionHandling()
            ->get(route('profiles.show', $teacher))
            ->assertSuccessful()
            ->viewData('user');

        $this->assertCount(3, $viewUser->teachingRecords);
        $this->assertEquals(
            [$newRecord->id, $midRecord->id, $oldRecord->id],
            $viewUser->teachingRecords->pluck('id')->toArray(),
            'teaching details are not in reverse chronological order'
        );
    }
}
