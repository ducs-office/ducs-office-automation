<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\ExternalAuthority;
use App\Models\Pivot\ScholarCosupervisor;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholars_can_be_viewed()
    {
        $this->signIn();

        $scholars = create(Scholar::class, 3)->each(function ($scholar) {
            $scholar->supervisors()->attach(factory(User::class)->states('supervisor')->create());
            create(ScholarCosupervisor::class, 1, ['scholar_id' => $scholar->id]);
        });

        $scholars = $this->withoutExceptionHandling()
            ->get(route('staff.scholars.index'))
            ->assertViewHas('scholars')
            ->viewData('scholars');

        $this->assertCount(3, $scholars);
    }

    /** @test */
    public function a_supervisor_can_view_only_scholars_whom_they_supervise_even_without_explicit_permission()
    {
        $supervisor = factory(User::class)->states('supervisor')->create();
        $supervisor->revokePermissionTo('scholars:view');

        $theirScholars = $supervisor->scholars()->createMany(
            make(Scholar::class, 3)->makeVisible('password')->toArray()
        );
        $otherScholars = create(Scholar::class, 5);

        $this->signIn($supervisor, null);
        $scholars = $this->withoutExceptionHandling()
            ->get(route('staff.scholars.index'))
            ->assertViewHas('scholars')
            ->viewData('scholars');

        $this->assertCount(3, $scholars);
        $this->assertEquals(collect($theirScholars)->pluck('id'), $scholars->pluck('id'));
    }

    /** @test */
    public function a_user_cannot_view_scholars_without_permission()
    {
        create(Scholar::class, 5);

        $teacher = create(User::class, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        $teacher->revokePermissionTo('scholars:view');

        $this->signIn($teacher, null);

        $this->withExceptionHandling()
            ->get(route('staff.scholars.index'))
            ->assertForbidden();
    }

    /** @test */
    public function view_has_a_unique_list_of_supervisors()
    {
        create(User::class, 3);
        $supervisors = factory(User::class, 3)->states('supervisor')->create();

        $this->signIn();

        $viewData = $this->withoutExceptionHandling()
            ->get(route('staff.scholars.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.scholars.index')
            ->assertViewHas('supervisors')
            ->viewData('supervisors');

        $this->assertCount(3, $viewData);
        $this->assertSame($supervisors->pluck('name', 'id')->toArray(), $viewData->toArray());
    }

    /** @test */
    public function view_has_a_unique_list_of_cosupervisors()
    {
        factory(User::class, 2)->create([
            'is_supervisor' => false,
            'is_cosupervisor' => false,
        ]);
        $supervisors = factory(User::class, 2)->states('supervisor')->create();
        $userCosupervisors = factory(User::class)->states('cosupervisor')->create();
        $externalCosupervisors = factory(User::class)->states(['cosupervisor', 'external'])->create();

        $this->signIn();

        $viewCosupervisors = $this->withoutExceptionHandling()
             ->get(route('staff.scholars.index'))
             ->assertSuccessful()
             ->assertViewIs('staff.scholars.index')
             ->assertViewHas('cosupervisors')
             ->viewData('cosupervisors');

        $this->assertCount(4, $viewCosupervisors);
    }
}
