<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Course;
use App\Models\CourseProgrammeRevision;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use App\Models\Teacher;
use App\Models\TeacherProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EditTeacherProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_edit_profile_details()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $college = create(College::class);
        $programme = create(Programme::class);
        $courses = create(Course::class, 2);
        create(ProgrammeRevision::class, 2);
        $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);

        foreach ($courses as $course) {
            $revision->courses()->attach($course, ['semester' => 1]);
        }

        $update = [
            'phone_no' => '9876543210',
            'address' => 'new address, New Delhi',
            'designation' => 'A',
            'college_id' => $college->id,
            'teaching_details' => [
                ['programme_revision_id' => $revision->id, 'course_id' => $courses[0]->id],
                ['programme_revision_id' => $revision->id, 'course_id' => $courses[1]->id],
            ],
            'profile_picture' => $profilePicture = UploadedFile::fake()->image('picture.jpeg'),
        ];

        $this->withoutExceptionHandling()
                ->patch(route('teachers.profile.update'), $update)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $this->assertEquals(1, TeacherProfile::count());

        $this->assertEquals($update['phone_no'], $teacher->profile->fresh()->phone_no);
        $this->assertEquals($update['address'], $teacher->profile->fresh()->address);
        $this->assertEquals($update['designation'], $teacher->profile->fresh()->designation);
        $this->assertEquals($update['college_id'], $teacher->profile->fresh()->college_id);

        $freshDetails = $teacher->profile->fresh()->teachingDetails;

        $this->assertEquals(2, $freshDetails->count());
        $this->assertEquals($freshDetails[0]->programme_revision_id, $revision->id);
        $this->assertEquals($freshDetails[1]->programme_revision_id, $revision->id);
        $this->assertEquals($freshDetails[0]->course_id, $courses[0]->id);
        $this->assertEquals($freshDetails[1]->course_id, $courses[1]->id);

        $this->assertEquals(
            'teacher_attachments/profile_picture/' . $profilePicture->hashName(),
            $teacher->profile->fresh()->profilePicture->path
        );
        Storage::assertExists('teacher_attachments/profile_picture/' . $profilePicture->hashName());
    }

    /** @test */
    public function request_validates_teaching_details_course_belongs_to_programme()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

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
                ->patch(route('teachers.profile.update'), $update)
                ->assertRedirect()
                ->assertSessionHasErrors('teaching_details.0.course_id');

        $this->assertEquals($teacher->profile->teachingDetails->count(), 0);
    }

    /** @test */
    public function teaching_details_requires_both_programme_revision_and_course()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

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
            ->patch(route('teachers.profile.update'), $update)
            ->assertRedirect()
            ->assertSessionHasErrors('teaching_details.0');

        $this->assertEquals(1, TeacherProfile::count());

        $this->assertEquals($teacher->profile->teachingDetails->count(), 0);
    }

    /** @test */
    public function request_validates_teacher_cannot_update_fields_to_empty()
    {
        $this->signInTeacher($teacher = create(Teacher::class));
        $teacher->profile->update(make(TeacherProfile::class, 1, ['teacher_id' => $teacher->id])->toArray());

        $update = [
            'phone_no' => '',
            'address' => '',
            'designation' => '',
            'college_id' => '',
        ];

        $this->withExceptionHandling()
            ->patch(route('teachers.profile.update'), $update)
            ->assertRedirect()
            ->assertSessionHasErrors(['phone_no', 'address', 'designation', 'college_id']);

        $this->assertEquals($teacher->profile->phone_no, $teacher->profile->fresh()->phone_no);
        $this->assertEquals($teacher->profile->address, $teacher->profile->fresh()->address);
        $this->assertEquals($teacher->profile->designation, $teacher->profile->fresh()->designation);
        $this->assertEquals($teacher->profile->college_id, $teacher->profile->fresh()->college_id);
    }

    /** @test */
    public function request_validates_teaching_details_can_be_added_even_without_filling_any_other_details()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $programme = create(Programme::class, 1, ['wef' => now()]);
        $courses = create(Course::class, 2);
        $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);

        foreach ($courses as $course) {
            $revision->courses()->attach($course, ['semester' => 1]);
        }

        $update = [
            'teaching_details' => [
                ['programme_revision_id' => $revision->id, 'course_id' => $courses[0]->id],
                ['programme_revision_id' => $revision->id, 'course_id' => $courses[1]->id],
            ],
        ];

        $this->withoutExceptionHandling()
            ->patch(route('teachers.profile.update'), $update)
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $this->assertEquals(1, TeacherProfile::count());
        $this->assertEquals(2, $teacher->profile->teachingDetails()->count());
    }

    /** @test */
    public function teaching_details_are_synced_with_already_teaching_details_other_details()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $otherCourseProgrammeDetails = create(CourseProgrammeRevision::class, 2);

        $existingCourseProgrammeDetails = $teacher->profile->teachingDetails()
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
            ->patch(route('teachers.profile.update'), $update)
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $this->assertEquals(1, TeacherProfile::count());
        $this->assertEquals(3, $teacher->profile->teachingDetails()->count());
        $this->assertEquals(
            $update['teaching_details'],
            $teacher->profile->fresh()->teachingDetails->map->only([
                'programme_revision_id',
                'course_id',
                'semester',
            ])->toArray()
        );
    }
}
