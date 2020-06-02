<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\ExternalAuthority;
use App\Models\Scholar;
use App\Models\User;
use Carbon\Carbon;
use CreateCosupervisorsTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ReplaceScholarMentorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholars_co_supervisor_is_replaced_while_keeping_the_history()
    {
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );
        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        $exisitngCosupervisor = factory(User::class)->states('cosupervisor')->create();
        $this->signIn();

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.cosupervisor.replace', $scholar), [
                'cosupervisor_id' => $exisitngCosupervisor->id,
            ])
            ->assertSessionHasFlash('success', 'Co-Supervisor replaced successfully!');

        $oldCosupervisors = $scholar->cosupervisors()
            ->where('ended_on', today())
            ->get();

        $newCosupervisors = $scholar->cosupervisors()
            ->wherePivot('ended_on', null)
            ->wherePivot('started_on', today())
            ->wherePivot('user_id', $exisitngCosupervisor->id)
            ->get();

        $this->assertCount(1, $oldCosupervisors);
        $this->assertCount(1, $newCosupervisors);
    }

    /** @test */
    public function scholars_co_supervisor_is_replaced_by_external_while_keeping_the_history()
    {
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );
        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        $externalCosupervisor = factory(User::class)->states(['cosupervisor', 'external'])->create();

        $this->signIn();

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.cosupervisor.replace', $scholar), [
                'cosupervisor_id' => $externalCosupervisor->id,
            ])
            ->assertSessionHasFlash('success', 'Co-Supervisor replaced successfully!');

        $oldCosupervisors = $scholar->cosupervisors()
            ->wherePivot('ended_on', today())
            ->get();

        $newCosupervisors = $scholar->cosupervisors()
            ->wherePivot('ended_on', null)
            ->wherePivot('started_on', today())
            ->wherePivot('user_id', $externalCosupervisor->id)
            ->get();

        $this->assertCount(1, $oldCosupervisors);
        $this->assertCount(1, $newCosupervisors);
    }

    /** @test */
    public function scholars_co_supervisor_is_replaced_by_another_supervisor_while_keeping_the_history()
    {
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );
        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);
        $anotherSupervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn();

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.cosupervisor.replace', $scholar), [
                'cosupervisor_id' => $anotherSupervisor->id,
            ])
            ->assertSessionHasFlash('success', 'Co-Supervisor replaced successfully!');

        $oldCosupervisors = $scholar->cosupervisors()
            ->where('ended_on', today())
            ->get();

        $newCosupervisors = $scholar->cosupervisors()
            ->wherePivot('ended_on', null)
            ->wherePivot('started_on', today())
            ->wherePivot('user_id', $anotherSupervisor->id)
            ->get();

        $this->assertCount(1, $oldCosupervisors);
        $this->assertCount(1, $newCosupervisors);
    }

    /** @test */
    public function scholars_cosupervisor_can_be_replaced_even_when_there_is_no_cosupervisor_assigned()
    {
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );
        $exisitngCosupervisor = factory(User::class)->states('cosupervisor')->create();
        // No cosupervisor assigned already
        // $scholar->cosupervisors()->attach(
        //     create(Cosupervisor::class)
        // );

        $this->signIn();

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.cosupervisor.replace', $scholar), [
                'cosupervisor_id' => $exisitngCosupervisor->id,
            ])
            ->assertSessionHasFlash('success', 'Co-Supervisor replaced successfully!');

        $this->assertCount(1, $scholar->fresh()->cosupervisors); // Only one new Cosupervisor

        $newCosupervisors = $scholar->cosupervisors()
            ->wherePivot('ended_on', null)
            // start date is recorded from today so that we know a term when there was no cosupervisor assigned.
            ->wherePivot('started_on', today())
            ->wherePivot('user_id', $exisitngCosupervisor->id)
            ->get();

        $this->assertCount(1, $newCosupervisors);
    }

    /** @test */
    public function cosupervisor_of_scholar_cannot_be_replaced_if_cosupervisor_is_same_as_previous_cosupervisor_or_current_supervisor()
    {
        $this->signIn();
        $supervisor = factory(User::class)->states('supervisor')->create();
        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);
        $scholar->cosupervisors()->attach($cosupervisor);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.scholars.cosupervisor.replace', $scholar), [
                    'cosupervisor_id' => $cosupervisor->id,
                ]);
            $this->fail('Cosupervisor was allowed to replace with the same current cosupervisor. Validation Error was expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('cosupervisor_id', $e->errors());
        }
        $this->assertCount(1, $scholar->fresh()->cosupervisors);

        // Now try replacing with current supervisor's cosupervisor id.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.scholars.cosupervisor.replace', $scholar), [
                    'cosupervisor_id' => $supervisor->id,
                ]);
            $this->fail('cosupervisor was allowed to replace with the current supervisor. Validation Errror was expected');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('cosupervisor_id', $e->errors());
        }

        $this->assertCount(1, $scholar->fresh()->cosupervisors);
    }

    /** @test */
    public function supervisor_of_scholar_can_be_replaced()
    {
        ($scholar = create(Scholar::class))->supervisors()->attach(
            $supervisor = factory(User::class)->states('supervisor')->create()
        );
        $exisitngSupervisor = factory(User::class)->states('supervisor')->create();

        $this->signIn();

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.supervisor.replace', $scholar), [
                'supervisor_id' => $exisitngSupervisor->id,
            ])
            ->assertSessionHasFlash('success', 'Supervisor replaced successfully!');

        $oldSupervisors = $scholar->supervisors()
            ->wherePivot('ended_on', today())
            ->where('supervisor_id', $supervisor->id)
            ->get();

        $newSupervisors = $scholar->supervisors()
            ->wherePivot('ended_on', null)
            ->wherePivot('started_on', today())
            ->where('supervisor_id', $exisitngSupervisor->id)
            ->get();

        $this->assertCount(1, $oldSupervisors);
        $this->assertCount(1, $newSupervisors);
    }

    /** @test */
    public function supervisor_of_scholar_can_not_be_replaced_if_supervisor_is_same_as_previous_supervisor()
    {
        ($scholar = create(Scholar::class))->supervisors()->attach(
            $supervisor = factory(User::class)->states('supervisor')->create()
        );

        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.scholars.supervisor.replace', $scholar), [
                    'supervisor_id' => $supervisor->id,
                ]);
            $this->fail('Supervisor was allowed to replace by the same supervisor. Validation Error was expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('supervisor_id', $e->errors());
        }

        $this->assertCount(1, $scholar->refresh()->supervisors);
        $this->assertEquals($supervisor->id, $scholar->supervisors->first()->id);
    }
}
