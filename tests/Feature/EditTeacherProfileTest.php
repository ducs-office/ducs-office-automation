<?php

namespace Tests\Feature;

use App\Programme;
use App\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\TeacherProfile;
use App\Teacher;
use App\College;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
        $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);

        foreach ($courses as $course) {
            $revision->courses()->attach($course, ['semester' => 1]);
        }

        $update = [
            'phone_no' => '9876543210',
            'address' => 'new address, New Delhi',
            'designation' => 'G',
            'ifsc' => 'PNB098765498',
            'account_no' => '12234567890',
            'bank_name' => 'Punjab National Bank',
            'bank_branch' => 'Rejender Nagar, New Delhi',
            'college_id' => $college->id,
            'teaching_details' => [
                ['programme' => $programme->id, 'course' => $courses[0]->id],
                ['programme' => $programme->id, 'course' => $courses[1]->id]
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
        $this->assertEquals($update['ifsc'], $teacher->profile->fresh()->ifsc);
        $this->assertEquals($update['account_no'], $teacher->profile->fresh()->account_no);
        $this->assertEquals($update['bank_name'], $teacher->profile->fresh()->bank_name);
        $this->assertEquals($update['bank_branch'], $teacher->profile->fresh()->bank_branch);
        $this->assertEquals($update['college_id'], $teacher->profile->fresh()->college_id);

        $this->assertEquals($teacher->profile->teaching_details->count(), 2);
        $this->assertEquals($teacher->profile->teaching_details[0]->programme_revision_id, $revision->id);
        $this->assertEquals($teacher->profile->teaching_details[1]->programme_revision_id, $revision->id);
        $this->assertEquals($teacher->profile->teaching_details[0]->course_id, $courses[0]->id);
        $this->assertEquals($teacher->profile->teaching_details[1]->course_id, $courses[1]->id);

        $this->assertEquals('teacher_attachments/profile_picture/'.$profilePicture->hashName(), $teacher->profile->fresh()->profile_picture->path);
        Storage::assertExists('teacher_attachments/profile_picture/'.$profilePicture->hashName());
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
                ['programme' => $programme->id, 'course' => $unassignedCourse->id]
            ],
        ];

        try {
            $this->withoutExceptionHandling()
                ->patch(route('teachers.profile.update'), $update)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Profile Updated Successfully!');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('teaching_details.0', $e->errors());
        }

        $this->assertEquals($teacher->profile->teaching_details->count(), 0);
    }

    /** @test */
    public function teaching_details_are_not_updated_when_either_programme_or_course_not_provided()
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
                ['programme' => $revision->id],
            ],
        ];

        try {
            $this->withoutExceptionHandling()
                ->patch(route('teachers.profile.update'), $update)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Profile Updated Successfully!');
        } catch (ValidationException $e) {
            dd($e->errors());
        }

        $this->assertEquals(1, TeacherProfile::count());

        $this->assertEquals($teacher->profile->teaching_details->count(), 0);
    }

    /** @test */
    public function request_validates_teacher_can_update_all_fields_to_empty()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $programme = create(Programme::class, 1, ['wef' => now()]);
        $courses = create(Course::class, 2);
        $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);

        $update = [
            'phone_no' => '',
            'address' => '',
            'designation' => '',
            'ifsc' => '',
            'account_no' => '',
            'bank_name' => '',
            'bank_branch' => '',
            'college_id' => '',
            'teaching_details' =>'',
        ];


        foreach ($courses as $course) {
            $revision->courses()->attach($course, ['semester' => 1]);
        }

        $this->withoutExceptionHandling()
                ->patch(route('teachers.profile.update'), $update)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $this->assertEquals('', $teacher->profile->fresh()->phone_no);
        $this->assertEquals('', $teacher->profile->fresh()->address);
        $this->assertEquals('', $teacher->profile->fresh()->designation);
        $this->assertEquals('', $teacher->profile->fresh()->ifsc);
        $this->assertEquals('', $teacher->profile->fresh()->account_no);
        $this->assertEquals('', $teacher->profile->fresh()->bank_name);
        $this->assertEquals('', $teacher->profile->fresh()->bank_branch);
        $this->assertEquals('', $teacher->profile->fresh()->college_id);

        $this->assertEquals($teacher->profile->teaching_details->count(), 0);
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
                ['programme' => $programme->id, 'course' => $courses[0]->id],
                ['programme' => $programme->id, 'course' => $courses[1]->id]
            ],
        ];

        $this->withoutExceptionHandling()
                ->patch(route('teachers.profile.update'), $update)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $this->assertEquals(1, TeacherProfile::count());
        $this->assertEquals($teacher->profile->teaching_details->count(), 2);
    }
}
