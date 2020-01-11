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

        $programme1 = create(Programme::class);
        $course1 = create(Course::class);
        $course1->programmes()->attach([$programme1->id]);
        $programme2 = create(Programme::class);
        $course2 = $programme2->courses();

        try {
            $this->withoutExceptionHandling()
                ->patch('/programmes/'.$programme2->id, [
                'code' => $programme2->code,
                'wef' => $programme2->wef,
                'name' => $programme2->name,
                'type' => $programme2->type,
                'courses' => [$course1->id],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('courses.0', $e->errors());
        }

        $this->assertEquals(Programme::count(), 2);
        $this->assertEquals($course2->count(), $programme2->fresh()->courses()->count());

        $course1 = create(Course::class);
    
        $this->withoutExceptionHandling()
        ->patch('/programmes/'.$programme2->id, [
                'code' => $programme2->code,
                'wef' => $programme2->wef,
                'name' => $programme2->name,
                'type' => $programme2->type,
                'courses' => [$course1->id],
            ])->assertRedirect('/programmes')
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(Programme::count(), 2);
        $this->assertEquals(1, $programme2->fresh()->courses()->count());
    }

    /** @test */
    public function admin_can_delete_assigned_courses_to_the_programme()
    {
        $this->signIn();

        $programme = create(Programme::class);
        $courses = create(Course::class, 3);
        $programme->courses()->attach($courses->pluck('id'));

        $this->withoutExceptionHandling()
            -> patch('/programmes/'.$programme->id, [
                'courses' => [$courses[0]->id, $courses[1]->id]
            ])->assertRedirect('/programmes')
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'Programme updated successfully!');
        $this->assertEquals(2, $programme->fresh()->courses()->count());
    }

    /** @test */
    public function admin_can_update_type_field()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class, 1, ['type'=>'Under Graduate(U.G.)']);

        $response = $this->patch('/programmes/'.$programme->id, [
            'type' => $newType = 'Post Graduate(P.G.)'
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
