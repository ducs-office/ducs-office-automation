<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Course;
use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use App\Models\User;
use App\Types\Designation;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditTeacherProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function college_teacher_can_edit_profile_details()
    {
        $college = create(College::class);
        $programme = create(Programme::class);
        $courses = create(Course::class, 2);
        create(ProgrammeRevision::class, 2);
        $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);

        foreach ($courses as $course) {
            $revision->courses()->attach($course, ['semester' => 1]);
        }

        $this->signIn($teacher = create(User::class, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]));

        $update = [
            'phone' => '9876543210',
            'address' => 'new address, New Delhi',
            'status' => TeacherStatus::AD_HOC,
            'designation' => Designation::PROFESSOR,
            'college_id' => $college->id,
            'teaching_details' => [
                ['programme_revision_id' => $revision->id, 'course_id' => $courses[0]->id],
                ['programme_revision_id' => $revision->id, 'course_id' => $courses[1]->id],
            ],
            'avatar' => $avatar = UploadedFile::fake()->image('picture.jpg'),
        ];

        $this->withoutExceptionHandling()
                ->patch(route('profiles.update', $teacher), $update)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $teacher->refresh();

        $this->assertEquals($update['phone'], $teacher->phone);
        $this->assertEquals($update['address'], $teacher->address);
        $this->assertEquals($update['designation'], $teacher->designation);
        $this->assertEquals($update['college_id'], $teacher->college_id);

        $freshDetails = $teacher->teachingDetails;

        $this->assertEquals(2, $freshDetails->count());
        $this->assertEquals($freshDetails[0]->programme_revision_id, $revision->id);
        $this->assertEquals($freshDetails[1]->programme_revision_id, $revision->id);
        $this->assertEquals($freshDetails[0]->course_id, $courses[0]->id);
        $this->assertEquals($freshDetails[1]->course_id, $courses[1]->id);

        $expectedAvatarPath = 'users/avatars/' .
            md5(strtolower($teacher->email)) .
            '.' . $avatar->getClientOriginalExtension();

        Storage::assertExists($expectedAvatarPath);
        $this->assertEquals($expectedAvatarPath, $teacher->avatar_path);
    }

    /** @test */
    public function request_validates_teaching_details_course_belongs_to_programme()
    {
        $this->signIn($teacher = create(User::class));

        $programme = create(Programme::class, 1, ['wef' => now()]);
        $assignedCourse = create(Course::class);
        $unassignedCourse = create(Course::class);
        $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);

        $revision->courses()->attach($assignedCourse, ['semester' => 1]);

        $update = [
            'teaching_details' => [
                ['programme_revision_id' => $revision->id, 'course_id' => $unassignedCourse->id],
            ],
        ];

        $this->withExceptionHandling()
                ->patch(route('profiles.update', $teacher), $update)
                ->assertRedirect()
                ->assertSessionHasErrors('teaching_details.0.course_id');

        $this->assertEquals($teacher->teachingDetails->count(), 0);
    }

    /** @test */
    public function teaching_details_requires_both_programme_revision_and_course()
    {
        $this->signIn($teacher = create(User::class));

        $programme = create(Programme::class, 1, ['wef' => now()]);
        $courses = create(Course::class, 2);
        $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);

        foreach ($courses as $course) {
            $revision->courses()->attach($course, ['semester' => 1]);
        }

        $update = [
            'teaching_details' => [
                ['programme_revision_id' => $revision->id],
            ],
        ];

        $this->withExceptionHandling()
            ->patch(route('profiles.update', $teacher), $update)
            ->assertRedirect()
            ->assertSessionHasErrors('teaching_details.0');

        $this->assertCount(0, $teacher->teachingDetails);
    }

    /** @test */
    public function request_validates_teacher_cannot_update_fields_to_empty()
    {
        $this->signIn($teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
            'phone' => '9876543210',
            'address' => 'somewhere in, Delhi',
            'designation' => Designation::ASSISTANT_PROFESSOR,
            'status' => TeacherStatus::PERMANENT,
        ]));

        $update = [
            'phone' => null,
            'address' => null,
            'designation' => null,
            'college_id' => null,
        ];

        $this->withExceptionHandling()
            ->patch(route('profiles.update', $teacher), $update)
            ->assertRedirect()
            ->assertSessionHasErrors(['phone', 'address', 'designation', 'college_id']);

        tap($teacher->fresh(), function ($freshTeacher) use ($teacher) {
            $this->assertEquals($teacher->phone, $freshTeacher->phone);
            $this->assertEquals($teacher->address, $freshTeacher->address);
            $this->assertEquals($teacher->designation, $freshTeacher->designation);
            $this->assertEquals($teacher->college_id, $freshTeacher->college_id);
        });
    }

    /** @test */
    public function teaching_details_are_synced_with_already_teaching_details_other_details()
    {
        $this->signIn($teacher = create(User::class));

        $otherCourseProgrammeDetails = create(CourseProgrammeRevision::class, 2);

        $existingCourseProgrammeDetails = $teacher->teachingDetails()
            ->createMany(
                create(CourseProgrammeRevision::class, 2)->map->only([
                    'programme_revision_id',
                    'course_id',
                    'semester',
                ])->toArray()
            );

        $update = [
            'teaching_details' => $existingCourseProgrammeDetails->take(1)
                ->concat($otherCourseProgrammeDetails->take(2))
                ->map->only(['programme_revision_id', 'course_id', 'semester'])
                ->toArray(),
        ];

        $this->withoutExceptionHandling()
            ->patch(route('profiles.update', $teacher), $update)
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $teacher->refresh();

        $this->assertCount(3, $teacher->teachingDetails);
        $this->assertEquals(
            $update['teaching_details'],
            $teacher->teachingDetails->map->only([
                'programme_revision_id',
                'course_id',
                'semester',
            ])->toArray()
        );
    }
}
