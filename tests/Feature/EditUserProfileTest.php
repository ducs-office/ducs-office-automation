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

class EditUserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_profile_can_be_edited()
    {
        $user = factory(User::class)->states('external')->create();
        $this->signIn($user);

        $update = [
            'phone' => '9876543210',
            'address' => 'new address, New Delhi',
            'affiliation' => 'IIT Delhi',
        ];

        $this->withoutExceptionHandling()
            ->patch(route('profiles.update', $user), $update)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $user->refresh();

        $this->assertEquals($update['phone'], $user->phone);
        $this->assertEquals($update['address'], $user->address);
        $this->assertEquals($update['affiliation'], $user->affiliation);
    }

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
            'avatar' => $avatar = UploadedFile::fake()->image('picture.jpg'),
        ];

        $this->withoutExceptionHandling()
                ->patch(route('profiles.update', $teacher), $update)
                ->assertRedirect()
                ->assertSessionHasFlash('success', 'Profile Updated Successfully!');

        $teacher = $teacher->refresh();

        $this->assertEquals($update['phone'], $teacher->phone);
        $this->assertEquals($update['address'], $teacher->address);
        $this->assertEquals($update['designation'], $teacher->designation);
        $this->assertEquals($update['college_id'], $teacher->college_id);

        $expectedAvatarPath = 'users/avatars/' .
            md5(strtolower($teacher->email)) .
            '.' . $avatar->getClientOriginalExtension();

        Storage::assertExists($expectedAvatarPath);
        $this->assertEquals($expectedAvatarPath, $teacher->avatar_path);
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
            ->assertSessionHasErrors(['phone', 'address', 'designation', 'college_id'], null, 'update');

        tap($teacher->fresh(), function ($freshTeacher) use ($teacher) {
            $this->assertEquals($teacher->phone, $freshTeacher->phone);
            $this->assertEquals($teacher->address, $freshTeacher->address);
            $this->assertEquals($teacher->designation, $freshTeacher->designation);
            $this->assertEquals($teacher->college_id, $freshTeacher->college_id);
        });
    }
}
