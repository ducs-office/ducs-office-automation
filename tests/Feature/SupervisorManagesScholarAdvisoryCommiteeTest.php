<?php

namespace Tests\Feature;

use App\Models\ExternalAuthority;
use App\Models\Pivot\ScholarAdvisor;
use App\Models\Pivot\ScholarCosupervisor;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupervisorManagesScholarAdvisoryCommiteeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholars_current_advisors_are_updated_without_tracking_history()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        $externalAdvisors = factory(User::class, 2)->states('external')->create();
        $scholar->advisors()->attach($externalAdvisors, ['started_on' => today()]);
        $facultyCosupervisor = factory(User::class)->states('cosupervisor')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.advisors.update', $scholar), [
                'advisors' => [$facultyCosupervisor->id],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $this->assertCount(1, $scholar->refresh()->advisors);
        $this->assertCount(1, $advisors = $scholar->currentAdvisors);

        $this->assertEquals($facultyCosupervisor->id, $advisors->first()->id);
        // Created with same term as pervious even when changed after 10 days.
        $this->assertEquals(today()->subDays(10), $advisors->first()->pivot->started_on);
    }

    /** @test */
    public function scholars_new_current_advisors_are_added_without_tracking_history()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        // No advisors assigned yet.

        $facultyCosupervisor = factory(User::class)->states('cosupervisor')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.advisors.update', $scholar), [
                'advisors' => [$facultyCosupervisor->id],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);
        $this->assertEquals($facultyCosupervisor->id, $advisors->first()->id);

        // Created with the date when the scholar was created.
        $this->assertEquals($scholar->created_at, $advisors->first()->pivot->started_on);
    }

    /** @test */
    public function scholars_current_advisors_cannot_be_added_with_current_supervisor_cosupervisor_or_same_advisors()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        $existingExternals = factory(User::class, 2)->states('external')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        // Try adding current Supervisor and cosupervisor as advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.advisors.update', $scholar), [
                    'advisors' => [
                        $supervisor->id,
                        $cosupervisor->id,
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0', $e->errors(), 'Supervisor was allowed');
            $this->assertArrayHasKey('advisors.1', $e->errors(), 'Cosupervisor was allowed');
        }

        $this->assertCount(0, $advisors = $scholar->refresh()->currentAdvisors);

        // Try adding current same person as two different advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.advisors.update', $scholar), [
                    'advisors' => [
                        $existingExternals[0]->id,
                        $existingExternals[0]->id,
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0', $e->errors(), 'Duplicates were allowed');
            $this->assertArrayHasKey('advisors.1', $e->errors(), 'Duplicates were allowed');
        }

        $this->assertCount(0, $advisors = $scholar->refresh()->currentAdvisors);
    }

    /** @test */
    // Ignoring this test, this is not required, but keeping it here if we needed to revert

    // public function scholars_current_advisors_are_updated_without_tracking_history_with_new_externals_created_on_fly()
    // {
    //     $scholar = create(Scholar::class);

    //     $supervisor = factory(User::class)->states('supervisor')->create();
    //     $scholar->supervisors()->attach($supervisor);

    //     $cosupervisor = factory(User::class)->states('cosupervisor')->create();
    //     $scholar->cosupervisors()->attach($cosupervisor);

    //     $externalAdvisors = factory(User::class, 2)->states('external')->create();
    //     $scholar->advisors()->saveMany($externalAdvisors);

    //     // 10 Days later...
    //     Carbon::setTestNow(today()->addDays(10));

    //     $this->signIn($supervisor);

    //     $this->withoutExceptionHandling()
    //         ->patch(route('scholars.advisors.update', $scholar), [
    //             'advisors' => [
    //                 $external = make(ExternalAuthority::class)->attributesToArray(),
    //             ],
    //         ])->assertRedirect()
    //         ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

    //     $externalAuthorities = ExternalAuthority::query()
    //         ->where($external)
    //         ->get();
    //     $this->assertCount(1, $externalAuthorities);

    //     $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);

    //     $this->assertEquals(ExternalAuthority::class, $advisors->first()->advisor_type);
    //     $this->assertEquals($externalAuthorities->first()->id, $advisors->first()->advisor_id);

    //     // Created with same term as pervious even when changed after 10 days.
    //     $this->assertEquals(today()->subDays(10), $advisors->first()->pivot->started_on);
    // }

    /** @test */
    public function scholar_current_advisors_can_be_replaced_by_new_advisors_while_still_keeping_track_of_history()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        $currentExternalAdvisors = factory(User::class, 2)->states('external')->create();
        $scholar->advisors()->attach($currentExternalAdvisors);

        $facultyCosupervisor = factory(User::class)->states('cosupervisor')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.advisors.replace', $scholar), [
                'advisors' => [$facultyCosupervisor->id],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $freshOldAdvisors = $scholar->advisors()
            ->wherePivotIn('user_id', $currentExternalAdvisors->pluck('id')->toArray())
            ->get();
        $this->assertCount(2, $freshOldAdvisors);
        // modified with term, ending from today, i.e. after 10 days.
        $this->assertEquals(today(), $freshOldAdvisors[0]->pivot->ended_on);
        $this->assertEquals(today(), $freshOldAdvisors[1]->pivot->ended_on);

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);
        $this->assertEquals($facultyCosupervisor->id, $advisors->first()->id);

        // Created with new term, starting from today, i.e. after 10 days.
        $this->assertEquals(today(), $advisors->first()->pivot->started_on);
    }

    // Ignoring this test for the same reasons as above.
    // public function scholar_current_advisors_can_be_replaced_by_new_externals_craeted_on_the_fly_and_made_current_advisors()
    // {
    //     $scholar = create(Scholar::class);

    //     $supervisor = factory(User::class)->states('supervisor')->create();
    //     $scholar->supervisors()->attach($supervisor);

    //     $cosupervisor = factory(User::class)->states('cosupervisor')->create();
    //     $scholar->cosupervisors()->attach($cosupervisor);

    //     $currentExternalAdvisors = factory(User::class, 2)->states('external')->create();
    //     $scholar->advisors()->attach($currentExternalAdvisors);

    //     // 10 Days later...
    //     Carbon::setTestNow(today()->addDays(10));

    //     $this->signIn($supervisor);

    //     $this->withoutExceptionHandling()
    //         ->patch(route('scholars.advisors.replace', $scholar), [
    //             'advisors' => [
    //                 $external = make(ExternalAuthority::class)->attributesToArray(),
    //             ],
    //         ])->assertRedirect()
    //         ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

    //     $externalAuthorities = ExternalAuthority::where($external)->get();
    //     $this->assertCount(1, $externalAuthorities);
    //     $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);
    //     $this->assertEquals(ExternalAuthority::class, $advisors->first()->advisor_type);
    //     $this->assertEquals($externalAuthorities->first()->id, $advisors->first()->advisor_id);

    //     // Created with new term, starting from today, i.e. after 10 days.
    //     $this->assertEquals(today(), $advisors->first()->pivot->started_on);
    // }

    /** @test */
    public function scholars_current_advisors_cannot_be_replaced_with_current_supervisor_cosupervisor_or_same_advisors()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        $externalAdvisors = factory(User::class, 2)->states('external')->create();
        $scholar->advisors()->attach($externalAdvisors);

        $existingExternals = factory(User::class, 2)->states('external')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        // Try adding current Supervisor and cosupervisor as advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.advisors.replace', $scholar), [
                    'advisors' => [
                        $supervisor->id,
                        $cosupervisor->id,
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0', $e->errors(), 'Supervisor was allowed');
            $this->assertArrayHasKey('advisors.1', $e->errors(), 'Cosupervisor was allowed');
        }

        $this->assertCount(2, $advisors = $scholar->refresh()->advisors);

        // Try adding current same person as two different advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.advisors.replace', $scholar), [
                    'advisors' => [
                        $existingExternals[0]->id,
                        $existingExternals[0]->id,
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0', $e->errors(), 'Duplicates were allowed');
            $this->assertArrayHasKey('advisors.1', $e->errors(), 'Duplicates were allowed');
        }

        $this->assertCount(2, $advisors = $scholar->refresh()->advisors);
    }

    /** @test */
    public function scholar_advisors_are_replaced_if_no_current_advisors_assigned_does_nothing_and_redirects_with_message()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $facultyCosupervisor = factory(User::class)->states('cosupervisor')->create();

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.advisors.replace', $scholar), [
                'advisors' => [$facultyCosupervisor->id],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('warning', 'There must be advisors already assigned to be replaced.');

        $this->assertCount(0, $advisors = $scholar->refresh()->currentAdvisors);
    }
}
