<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewProgrammesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_programmes()
    {
        create(Programme::class, 3);

        $this->withExceptionHandling();

        $this->get(route('staff.programmes.index'))->assertRedirect();
    }

    /** @test */
    public function admin_can_view_all_programmes()
    {
        $this->signIn();

        $programmes = create(Programme::class, 3);
        $courses = create(Course::class, 3);

        foreach ($programmes as $index => $programme) {
            $revision = create(ProgrammeRevision::class, 1, ['programme_id' => $programme->id]);
            $revision->courses()->attach($courses[$index], ['semester' => 1]);
        }

        $this->withoutExceptionHandling();

        $view_data = $this->get(route('staff.programmes.index'))
            ->assertViewIs('staff.programmes.index')
            ->assertViewHasAll(['programmes', 'groupedCourses'])
            ->viewData('programmes');

        $this->assertCount(3, $view_data);

        $this->assertEquals(
            $programmes->sortByDesc('created_at')->first()->id,
            $view_data->first()->id
        ); //first created is at last
    }
}
