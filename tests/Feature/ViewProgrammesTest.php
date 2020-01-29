<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewProgrammesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_programmes()
    {
        create('App\Programme', 3);

        $this->withExceptionHandling();

        $this->get(route('staff.programmes.index'))->assertRedirect();
    }

    /** @test */
    public function admin_can_view_all_programmes()
    {
        $this->signIn();

        $programmes = create('App\Programme', 3);
        $courses = create('App\Course', 3);

        foreach ($programmes as $index => $programme) {
            $programmeRevision = $programme->revisions()->create(['revised_at' => $programme->wef]);
            $programmeRevision->courses()->attach($courses[$index], ['semester' => 1]);
        }

        $this->withoutExceptionHandling();

        $viewData = $this->get(route('staff.programmes.index'))
            ->assertViewIs('staff.programmes.index')
            ->assertViewHasAll(['programmes', 'grouped_courses'])
            ->viewData('programmes');

        $this->assertCount(3, $viewData);

        $this->assertEquals(
            $programmes->sortByDesc('created_at')->first()->id,
            $viewData->first()->id
        ); //first created is at last
    }
}
