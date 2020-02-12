<?php

namespace Tests\Feature;

use App\College;
use App\Course;
use App\Teacher;
use App\TeacherProfile;
use App\ProgrammeRevision;
use App\CourseProgrammeRevision;
use App\PastTeachersProfile;
use App\PastTeachingDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeachersProfileTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function teacher_can_view_their_profiles()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $response = $this->withoutExceptionHandling()
            ->get(route('teachers.profile'))
            ->assertSuccessful()
            ->assertViewHasAll([
                'teacher',
                'designations'
            ])
            ->assertSee($teacher->name);
    }

    /** @test */
    public function previous_submitted_profiles_of_teacher_can_be_viewed()
    {
        $this->signInTeacher($teacher = create(Teacher::class));
        
        $college = create(college::class);

        $teacher->past_profiles()->createMany([
            [
                'college_id' => $college->id,
                'designation' => $this->faker->randomElement(array_keys(config('options.teachers.designations'))),
                'valid_from' => $this->faker->date,
            ],
            [
                'college_id' => $college->id,
                'designation' => $this->faker->randomElement(array_keys(config('options.teachers.designations'))),
                'valid_from' => $this->faker->date,
            ],
        ]);

        $programme_revisions = create(ProgrammeRevision::class, 3);
        $courses = create(Course::class, 3);

        foreach ($programme_revisions as $index => $programme_revision) {
            $programme_revision->courses()->attach([
                    [
                        'course_id' => $courses[$index]->id,
                        'semester' => $index + 1
                    ]
            ]);
        }

        $teacher->past_profiles[0]->past_teaching_details()
            ->attach([ 1, 2 ]);

        $teacher->past_profiles[1]->past_teaching_details()->attach(3);

        $view_profile = $this->withoutExceptionHandling()
            ->get(route('teachers.profile'))
            ->assertSuccessful()
            ->viewData('teacher');

        // dd($view_profile['past_profiles']);
        
        // $this->assertTrue($view_profile['past_profiles']->contains($teacher->past_profiles()));
    }
}
