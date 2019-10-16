<?php

namespace Tests\Feature;

use App\Course;
use App\Paper;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePaperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_paper_code()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $paper = create(Paper::class);

        $response = $this->patch('/papers/'.$paper->id, [
            'code' => $newCode = 'New123'
        ])->assertRedirect('/papers')
        ->assertSessionHasFlash('success', 'Paper updated successfully!');

        $this->assertEquals(1, Paper::count());
        $this->assertEquals($newCode, $paper->fresh()->code);
    }

    /** @test */
    public function admin_can_update_paper_name()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $paper = create(Paper::class);

        $response = $this->patch('/papers/' . $paper->id, [
            'name' => $newName = 'New paper'
        ])->assertRedirect('/papers')
        ->assertSessionHasFlash('success', 'Paper updated successfully!');

        $this->assertEquals(1, Paper::count());
        $this->assertEquals($newName, $paper->fresh()->name);
    }

    /** @test */
    public function admin_can_update_papers_related_course()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $paper = create(Paper::class);
        $newCourse = create(Course::class);

        $response = $this->patch('/papers/' . $paper->id, [
            'course_id' => $newCourse->id
        ])->assertRedirect('/papers')
        ->assertSessionHasFlash('success', 'Paper updated successfully!');

        $this->assertEquals(1, Paper::count());
        $this->assertEquals($newCourse->id, $paper->fresh()->course_id);
    }

    /** @test */
    public function paper_is_not_validated_for_uniqueness_if_code_is_not_changed()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $paper = create(Paper::class);

        $response = $this->patch('/papers/'.$paper->id, [
            'code' => $paper->code,
            'name' => $newName = 'New paper'
        ])->assertRedirect('/papers')
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'Paper updated successfully!');


        $this->assertEquals(1, Paper::count());
        $this->assertEquals($newName, $paper->fresh()->name);
    }
}
