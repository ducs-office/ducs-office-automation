<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\College;
use App\Programme;

class UpdateCollegeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_update_a_college()
    {
        $college = create(College::class);

        $this->withExceptionHandling()
            ->patch('/colleges/'. $college->id, ['code' => 'code1'])
            ->assertRedirect('/login');


        $this->assertEquals($college->code, $college->fresh()->code);
    }
    /** @test */
    public function admin_can_update_a_college_code()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch('/colleges/'. $college->id, ['code' => $new_code = 'code1'])
            ->assertRedirect('/colleges')
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());
        $this->assertEquals($new_code, $college->fresh()->code);
    }

    /** @test */
    public function admin_can_update_a_college_name()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->patch('/colleges/'. $college->id, ['name' => $new_name = 'new name'])
            ->assertRedirect('/colleges')
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());
        $this->assertEquals($new_name, $college->fresh()->name);
    }

    /** @test */
    public function admin_can_update_a_college_programmes()
    {
        $this->signIn();

        $college = create(College::class);
        $programmes = create(Programme::class, 5);

        $college->programmes()->attach([$programmes[0]->id, $programmes[1]->id, $programmes[2]->id]);

        $this->withoutExceptionHandling()
            ->patch('/colleges/'. $college->id, [
                'programmes' => $new_programmes = [$programmes[3]->id, $programmes[4]->id]
                ])
            ->assertRedirect('/colleges')
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());

        $college_programmes = $college->fresh()->programmes()->pluck('id')->toArray();
        $this->assertSame($college_programmes, $new_programmes);
    }

    /** @test */
    public function college_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $college = create(College::class);

        $response = $this->patch('/colleges/'.$college->id, [
            'code' => $college->code,
            'name' => $newName = 'New college'
        ])->assertRedirect('/colleges')
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'College updated successfully!');


        $this->assertEquals(1, College::count());
        $this->assertEquals($newName, $college->fresh()->name);
    }

    /** @test */
    public function college_is_not_validated_for_uniqueness_if_name_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $college = create(College::class);

        $response = $this->patch('/colleges/'.$college->id, [
            'code' => $new_code = 'new_code123',
            'name' => $college->name
        ])->assertRedirect('/colleges')
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'College updated successfully!');


        $this->assertEquals(1, College::count());
        $this->assertEquals($new_code, $college->fresh()->code);
    }
}
