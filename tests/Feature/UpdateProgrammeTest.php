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
    public function programme_code_can_be_updates()
    {
        $this->withoutExceptionHandling()
            ->signIn(create(User::class), 'admin');

        $programme = create(Programme::class);

        $response = $this->patch(route('staff.programmes.update', $programme), [
            'code' => $newCode = 'New123'
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newCode, $programme->fresh()->code);
    }

    /** @test */
    public function programme_date_wef_can_be_updated()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class);
        $course = create(Course::class);

        $programmeRevision = $programme->revisions()->create(['revised_at' => $programme->wef]);
        $programmeRevision->courses()->attach($course, ['semester' => 1]);

        $response = $this->patch(route('staff.programmes.update', $programme), [
            'wef' => $newDate = now()->format('Y-m-d H:i:s')
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newDate, $programme->fresh()->wef);
    }

    /** @test */
    public function programme_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class);

        $response = $this->patch(route('staff.programmes.update', $programme), [
            'code' => $programme->code,
            'name' => $newName = 'New Programme'
        ])->assertRedirect()
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newName, $programme->fresh()->name);
    }

    /** @test */
    public function type_field_can_be_updated()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class, 1, ['type'=>'UG']);

        $response = $this->patch(route('staff.programmes.update', $programme), [
            'type' => $newType = 'PG'
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Programme updated successfully!');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals($newType, $programme->fresh()->type);
    }
}
