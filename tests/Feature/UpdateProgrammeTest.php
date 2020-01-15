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
        $course = create(Course::class);

        $programme->courses()->attach($course, ['semester' => 1, 'revised_on' => now()->format('y-m-d')]);

        $response = $this->patch('/programmes/'.$programme->id, [
            'wef' => $newDate = now()->addYear(1)->format('y-m-d')
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newDate, $programme->fresh()->wef);
    }

    /** @test */
    public function request_validates_wef_is_greater_than_the_latest_revision()
    {
        $this->signIn();
        $programme = create(Programme::class);
        $courses = create(Course::class, 2);

        $programme->courses()->attach($courses[0], ['semester' => 1, 'revised_on' => now()->format('y-m-d')]);
        $programme->courses()->attach($courses[1], ['semester' => 1, 'revised_on' => now()->addYear(1)->format('y-m-d')]);

        try {
            $this->patch('/programmes/'.$programme->id, [
                'wef' => now()->format('y-m-d')
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('wef', $e->errors());
        }

        $this->withoutExceptionHandling()
            ->patch('/programmes/'.$programme->id, [
                'wef' => now()->addYear(2)->format('Y-m-d')
            ])->assertRedirect('/programmes');

        $this->assertEquals(1, Programme::count());
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
}
