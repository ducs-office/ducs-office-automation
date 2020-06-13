<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\User;
use App\Types\UserCategory;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScholarResearchCommitteeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisors_are_returned_ordered_by_their_started_on_date_in_desc_order()
    {
        ($scholar = create(Scholar::class))->supervisors()->attach(
            $supervisor = factory(User::class)->states('supervisor')->create()
        );

        $newSupervisor = factory(User::class)->states('supervisor')->create();

        Carbon::setTestNow(today()->addDays(10)); // 10 days later

        $this->signIn();

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.supervisor.replace', $scholar), [
                'supervisor_id' => $newSupervisor->id,
            ])
            ->assertSessionHasFlash('success', 'Supervisor replaced successfully!');

        $allSupervisors = $scholar->supervisors;

        $this->assertEquals($newSupervisor->id, $allSupervisors[0]->id);
        $this->assertEquals($supervisor->id, $allSupervisors[1]->id);
    }

    /** @test */
    public function cosupervisors_are_returned_ordered_by_their_started_on_date_in_desc_order()
    {
        ($scholar = create(Scholar::class))->cosupervisors()->attach(
            $cosupervisor = factory(User::class)->states('cosupervisor')->create()
        );

        $supervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $newCosupervisor = factory(User::class)->states('cosupervisor')->create();

        Carbon::setTestNow(today()->addDays(10)); // 10 days later

        $this->signIn();

        $this->withoutExceptionHandling()
             ->patch(route('staff.scholars.cosupervisor.replace', $scholar), [
                 'cosupervisor_id' => $newCosupervisor->id,
             ])
             ->assertSessionHasFlash('success', 'Co-Supervisor replaced successfully!');

        $allCosupervisors = $scholar->cosupervisors;

        $this->assertEquals($newCosupervisor->id, $allCosupervisors[0]->id);
        $this->assertEquals($cosupervisor->id, $allCosupervisors[1]->id);
    }

    /** @test */
    public function advisors_are_returned_ordered_by_their_started_on_date_in_desc_order()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $advisors = create(User::class, 2);
        $scholar->advisors()->attach($advisors);

        $newAdvisors = create(User::class, 2, [
            'category' => UserCategory::FACULTY_TEACHER,
        ]);

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.advisors.replace', $scholar), [
                'advisors' => $newAdvisors->pluck('id')->toArray(),
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $allAdvisors = $scholar->advisors;

        $this->assertEquals(
            $newAdvisors->pluck('id')->toArray(),
            [$allAdvisors[0]->id, $allAdvisors[1]->id]
        );

        $this->assertEquals(
            $advisors->pluck('id')->toArray(),
            [$allAdvisors[2]->id, $allAdvisors[3]->id]
        );
    }
}
