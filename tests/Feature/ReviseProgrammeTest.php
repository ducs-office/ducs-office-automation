<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Course;
use App\Programme;
use Illuminate\Validation\ValidationException;

class ReviseProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_revise_programme()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class, 1, ['wef' => '1973-02-08', 'duration' => '1']);
        
        $semester_courses = create(Course::class, 2);

        foreach ($semester_courses as $index => $course) {
            $programme->courses()->attach($course->id, ['semester' => $index + 1, 'revised_on' => $programme->wef]);
        }
       
        $revised_on = '2000-02-01';

        $this->patch("/programmes/$programme->id/revise", ['revised_on' => $revised_on, 'semester_courses' => [[$semester_courses[0]->id], [$semester_courses[1]->id]]])
            ->assertRedirect('/programmes')
            ->assertSessionHasFlash('success', 'Programme revised successfully!');
       
        $this->assertEquals(1, Programme::count());
        $this->assertEquals($revised_on, Programme::find(1)->courses->map->pivot->max('revised_on'));
    }

    /** @test */
    public function request_validates_revised_on_field_is_required()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $courses = create(Course::class, 2);
        
        foreach ($courses as $index => $course) {
            $programme->courses()->attach($course->id, ['semester' => $index + 1, 'revised_on' => $programme->wef]);
        }

        try {
            $this->withoutExceptionHandling()
                ->patch(
                    "programmes/$programme->id/revise",
                    [
                        'semester_courses' => [[$courses[0]->id], [$courses[1]->id]]
                    ]
                );
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_on', $e->errors());
        }

        $this->assertEquals($programme->wef, Programme::find(1)->courses->map->pivot->max('revised_on'));
    }

    /** @test */
    public function request_validates_revised_on_field_is_date()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $courses = create(Course::class, 2);
        
        foreach ($courses as $index => $course) {
            $programme->courses()->attach($course->id, ['semester' => $index + 1, 'revised_on' => $programme->wef]);
        }

        try {
            $this->withoutExceptionHandling()
                ->patch("programmes/$programme->id/revise", [
                        'revised_on' => 'some random string',
                        'semester_courses' => [[$courses[0]->id], [$courses[1]->id]]
                    ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_on', $e->errors());
        }

        $this->assertEquals($programme->wef, Programme::find(1)->courses->map->pivot->max('revised_on'));

        $revised_on = "2019-09-08";

        $this->withoutExceptionHandling()
        ->patch("programmes/$programme->id/revise", [
            'revised_on' => $revised_on,
            'semester_courses' => [[$courses[0]->id], [$courses[1]->id]],
        ]);
    
        $this->assertEquals($revised_on, Programme::find(1)->courses->map->pivot->max('revised_on'));
    }

    /** @test */
    public function request_validates_revised_on_field_is_greater_than_wef_field_of_programme()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $courses = create(Course::class, 2);
        
        foreach ($courses as $index => $course) {
            $programme->courses()->attach($course->id, ['semester' => $index + 1, 'revised_on' => $programme->wef]);
        }

        $revised_on = "1992-09-02";

        try {
            $this->withoutExceptionHandling()
                ->patch("programmes/$programme->id/revise", [
                        'revised_on' => $revised_on,
                        'semester_courses' => [[$courses[0]->id], [$courses[1]->id]]
                    ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_on', $e->errors());
        }

        $this->assertEquals($programme->wef, Programme::find(1)->courses->map->pivot->max('revised_on'));

        $revised_on = "2019-09-08";

        $this->withoutExceptionHandling()
            ->patch("programmes/$programme->id/revise", [
                'revised_on' => $revised_on,
                'semester_courses' => [[$courses[0]->id], [$courses[1]->id]],
            ]);
        
        $this->assertEquals($revised_on, Programme::find(1)->courses->map->pivot->max('revised_on'));
    }

    /** @test */
    public function wef_field_of_proramme_updates_when_revised_on_field_updates()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $courses = create(Course::class, 2);
        
        foreach ($courses as $index => $course) {
            $programme->courses()->attach($course->id, ['semester' => $index + 1, 'revised_on' => $programme->wef]);
        }

        $revised_on = "2019-09-08";

        $this->withoutExceptionHandling()
        ->patch("programmes/$programme->id/revise", [
            'revised_on' => $revised_on,
            'semester_courses' => [[$courses[0]->id], [$courses[1]->id]],
        ]);
    
        $this->assertEquals($revised_on, Programme::find(1)->wef);
    }

    /** @test */
    public function admin_can_add_only_non_assigned_courses_to_the_programme()
    {
        $this->signIn();

        $assignedCourse = create(Course::class);
        $programme = create(Programme::class, 1, ['wef' => '1999-09-08', 'duration' => '1']);
        $revised_on = '2000-09-11';
        $assignedCourse->programmes()->attach([$programme->id], ['semester' => 1, 'revised_on' => $programme->wef]);
        $unassignedCourses = create(Course::class, 2);

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => '1999-09-08']);

        try {
            $this->withoutExceptionHandling()
                ->patch('/programmes/'. $programme->id.'/revise', [
                    'revised_on' => $revised_on,
                    'semester_courses' => [
                        [$assignedCourse->id],
                        [$unassignedCourses[0]->id]
                    ],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.0.0', $e->errors());
        }

        $this->assertEquals(0, $programme->fresh()->courses()->count());

        $this->withoutExceptionHandling()
            ->patch('/programmes/'.$programme->id.'/revise', [
                'revised_on' => $revised_on,
                'semester_courses' => [
                    [$unassignedCourses[0]->id],
                    [$unassignedCourses[1]->id]
                ],
            ])->assertRedirect('/programmes')
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Programme revised successfully!');

        $this->assertEquals(2, $programme->fresh()->courses()->count());
    }

    /** @test */
    public function admin_can_move_assigned_courses_to_the_other_semester_of_programme()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['wef' => '2000-10-10', 'duration' => 1]);
        $courses = create(Course::class, 3);
        $programme->courses()->attach($courses, ['semester' => 1, 'revised_on' => $programme->wef]);

        $this->withoutExceptionHandling()
            ->patch('/programmes/'.$programme->id.'/revise', [
                'revised_on' => '2019-09-09',
                'semester_courses' => [
                    [$courses[0]->id, $courses[1]->id],
                    [$courses[2]->id],
                ]
            ])->assertRedirect('/programmes')
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Programme revised successfully!');

        $this->assertEquals(5, $programme->fresh()->courses()->wherePivot('semester', 1)->count());
        $this->assertEquals(1, $programme->fresh()->courses()->wherePivot('semester', 2)->count());
    }

    /** @test */
    public function admin_can_not_assign_same_courses_to_different_semester_of_programme()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['wef' => '2000-09-09', 'duration' => '1']);
        $courses = create(course::class, 2);
       
        foreach ($courses as $index => $course) {
            $programme->courses()->attach($course->id, ['semester' => $index + 1, 'revised_on' => $programme->wef]);
        }

        $revised_on = "2019-09-08";

        try {
            $this->withoutExceptionHandling()
            ->patch("programmes/$programme->id/revise", [
                'revised_on' => $revised_on,
                'semester_courses' => [[$courses[0]->id], [$courses[1]->id, $courses[0]->id]],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.1.1', $e->errors());
        }
        
        $this->assertEquals(1, $programme->fresh()->courses()->wherePivot('semester', 2)->count());
    }
}
