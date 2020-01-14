<?php

namespace Tests\Feature;

use App\Programme;
use App\Course;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class UpdateProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_programme_code()
    {
        $this->withoutExceptionHandling()
            ->signIn(create(User::class), 'admin');

        $programme = create(Programme::class);

        $response = $this->patch('/programmes/'.$programme->id, [
            'code' => $newCode = 'New123'
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newCode, $programme->fresh()->code);
    }

    /** @test */
    public function admin_can_update_programme_date_wef()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class);

        $response = $this->patch('/programmes/'.$programme->id, [
            'wef' => $newDate = '2014-05-10'
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newDate, $programme->fresh()->wef);
    }

    /** @test */
    public function admin_can_update_programme_name()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class);

        $response = $this->patch('/programmes/'.$programme->id, [
            'name' => $newName = 'New Programme'
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newName, $programme->fresh()->name);
    }

    /** @test */
    public function programme_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class);

        $response = $this->patch('/programmes/'.$programme->id, [
            'code' => $programme->code,
            'name' => $newName = 'New Programme'
        ])->assertRedirect('/programmes')
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newName, $programme->fresh()->name);
    }

    /** @test */
    public function admin_can_add_only_non_assigned_courses_to_the_programme()
    {
        $this->signIn();

        $assignedCourse = create(Course::class);
        $assignedCourse->programmes()->attach([create(Programme::class)->id], ['semester' => 1]);
        $unassignedCourses = create(Course::class, 2);

        $programme = create(Programme::class, 1, ['duration' => 1]);

        try {
            $this->withoutExceptionHandling()
                ->patch('/programmes/'. $programme->id, [
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
            ->patch('/programmes/'.$programme->id, [
                'semester_courses' => [
                    [$unassignedCourses[0]->id],
                    [$unassignedCourses[1]->id]
                ],
            ])->assertRedirect('/programmes')
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(2, $programme->fresh()->courses()->count());
    }

    /** @test */
    public function admin_can_move_assigned_courses_to_the_other_semester_of_programme()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1]);
        $courses = create(Course::class, 3);
        $programme->courses()->attach($courses, ['semester' => 1]);

        $this->withoutExceptionHandling()
            ->patch('/programmes/'.$programme->id, [
                'semester_courses' => [
                    [$courses[0]->id, $courses[1]->id],
                    [$courses[2]->id],
                ]
            ])->assertRedirect('/programmes')
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(2, $programme->fresh()->courses()->wherePivot('semester', 1)->count());
        $this->assertEquals(1, $programme->fresh()->courses()->wherePivot('semester', 2)->count());
    }

    /** @test */
    public function admin_can_update_type_field()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class, 1, ['type'=>'UG']);

        $response = $this->patch('/programmes/'.$programme->id, [
            'type' => $newType = 'PG'
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newType, $programme->fresh()->type);
    }

    /** @test */
    public function admin_can_update_duration_field()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class, 1, ['duration'=> 2]);

        $response = $this->patch('/programmes/'.$programme->id, [
            'duration' => $newDuration = 3
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newDuration, $programme->fresh()->duration);
    }
}
