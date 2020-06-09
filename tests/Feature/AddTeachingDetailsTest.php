<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\Programme;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddTeachingDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_teaching_details()
    {
        $this->signIn($teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]));

        $otherCourseProgrammeDetails = create(CourseProgrammeRevision::class, 2);

        $updateParam = $otherCourseProgrammeDetails->random()
            ->only(['programme_revision_id', 'course_id']);

        $this->withoutExceptionHandling()
            ->post(route('teaching-details.store'), $updateParam)
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $teacher->refresh();

        $this->assertCount(1, $teacher->teachingDetails);
        $this->assertEquals(
            [$updateParam],
            $teacher->teachingDetails->map->only([
                'programme_revision_id',
                'course_id',
            ])->toArray()
        );
    }

    /** @test */
    public function teaching_details_requires_both_programme_revision_and_course()
    {
        $this->signIn($teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]));

        $this->withExceptionHandling()
            ->post(route('teaching-details.store'))
            ->assertRedirect()
            ->assertSessionHasErrors(['programme_revision_id', 'course_id']);

        $this->assertCount(0, $teacher->fresh()->teachingDetails);
    }

    /** @test */
    public function request_validates_teaching_details_course_belongs_to_programme()
    {
        $this->signIn($teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]));

        $programme = create(Programme::class, 1);
        $revision = $programme->revisions()->create(['revised_at' => now()->subYear()]);

        $assignedCourse = create(Course::class);
        $unassignedCourse = create(Course::class);

        $revision->courses()->attach($assignedCourse, ['semester' => 1]);

        $this->withExceptionHandling()
            ->post(route('teaching-details.store'), [
                'programme_revision_id' => $revision->id,
                'course_id' => $unassignedCourse->id,
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('course_id');

        $this->assertCount(0, $teacher->fresh()->teachingDetails);
    }
}
