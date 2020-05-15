<?php

namespace Tests\Feature;

use App\Models\ExternalAuthority;
use App\Models\Scholar;
use App\Models\ScholarAdvisor;
use App\Models\ScholarCosupervisor;
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

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $externalAdvisors = make(ScholarAdvisor::class, 2, ['advisor_type' => ExternalAuthority::class]);
        $scholar->advisors()->saveMany($externalAdvisors);

        $facultyCosupervisor = factory(User::class)->states('cosupervisor')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisors.update', $scholar), [
                'advisors' => [
                    ['user_id' => $facultyCosupervisor->id],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $this->assertDeleted($externalAdvisors[0]);
        $this->assertDeleted($externalAdvisors[1]);

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);

        $this->assertEquals(User::class, $advisors->first()->advisor_type);
        $this->assertEquals($facultyCosupervisor->id, $advisors->first()->advisor_id);

        // Created with same term as pervious even when changed after 10 days.
        $this->assertEquals(today()->subDays(10), $advisors->first()->started_on);
    }

    /** @test */
    public function scholars_new_current_advisors_are_added_without_tracking_history()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $facultyCosupervisor = factory(User::class)->states('cosupervisor')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisors.update', $scholar), [
                'advisors' => [
                    ['user_id' => $facultyCosupervisor->id],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);
        $this->assertEquals(User::class, $advisors->first()->advisor_type);
        $this->assertEquals($facultyCosupervisor->id, $advisors->first()->advisor_id);

        // Created with same term as pervious even when changed after 10 days.
        $this->assertEquals($scholar->created_at, $advisors->first()->started_on);
    }

    /** @test */
    public function scholars_current_advisors_are_updated_without_tracking_history_with_existing_externals()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $externalAdvisors = make(ScholarAdvisor::class, 2, ['advisor_type' => ExternalAuthority::class]);
        $scholar->advisors()->saveMany($externalAdvisors);

        $existingExternal = create(ExternalAuthority::class);

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisors.update', $scholar), [
                'advisors' => [
                    ['external_id' => $existingExternal->id],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $this->assertDeleted($externalAdvisors[0]);
        $this->assertDeleted($externalAdvisors[1]);

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);

        $this->assertEquals(ExternalAuthority::class, $advisors->first()->advisor_type);
        $this->assertEquals($existingExternal->id, $advisors->first()->advisor_id);

        // Created with same term as pervious even when changed after 10 days.
        $this->assertEquals(today()->subDays(10), $advisors->first()->started_on);
    }

    /** @test */
    public function scholars_current_advisors_cannot_be_added_with_current_supervisor_cosupervisor_or_same_advisors()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->create([
            'person_type' => User::class,
            'person_id' => $cosupervisor->id,
        ]);

        $existingExternals = create(ExternalAuthority::class, 2);

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        // Try adding current Supervisor and cosupervisor as advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('research.scholars.advisors.update', $scholar), [
                    'advisors' => [
                        ['user_id' => $supervisor->id],
                        ['user_id' => $cosupervisor->id],
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0.user_id', $e->errors(), 'Supervisor was allowed');
            $this->assertArrayHasKey('advisors.1.user_id', $e->errors(), 'Cosupervisor was allowed');
        }

        $this->assertCount(0, $advisors = $scholar->refresh()->currentAdvisors);

        // Try adding current same person as two different advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('research.scholars.advisors.update', $scholar), [
                    'advisors' => [
                        ['external_id' => $existingExternals[0]->id],
                        ['external_id' => $existingExternals[0]->id],
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0.external_id', $e->errors(), 'Duplicates were allowed');
            $this->assertArrayHasKey('advisors.1.external_id', $e->errors(), 'Duplicates were allowed');
        }

        $this->assertCount(0, $advisors = $scholar->refresh()->currentAdvisors);
    }

    /** @test */
    public function scholars_current_advisors_are_updated_without_tracking_history_with_new_externals_created_on_fly()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $externalAdvisors = make(ScholarAdvisor::class, 2, ['advisor_type' => ExternalAuthority::class]);
        $scholar->advisors()->saveMany($externalAdvisors);

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisors.update', $scholar), [
                'advisors' => [
                    $external = make(ExternalAuthority::class)->attributesToArray(),
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $externalAuthorities = ExternalAuthority::query()
            ->where($external)
            ->get();
        $this->assertCount(1, $externalAuthorities);

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);

        $this->assertEquals(ExternalAuthority::class, $advisors->first()->advisor_type);
        $this->assertEquals($externalAuthorities->first()->id, $advisors->first()->advisor_id);

        // Created with same term as pervious even when changed after 10 days.
        $this->assertEquals(today()->subDays(10), $advisors->first()->started_on);
    }

    /** @test */
    public function scholar_current_advisors_can_be_replaced_by_new_advisors_while_still_keeping_track_of_history()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $currentExternalAdvisors = make(ScholarAdvisor::class, 2, ['advisor_type' => ExternalAuthority::class]);
        $scholar->advisors()->saveMany($currentExternalAdvisors);

        $facultyCosupervisor = factory(User::class)->states('cosupervisor')->create();

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisors.replace', $scholar), [
                'advisors' => [
                    ['user_id' => $facultyCosupervisor->id],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $freshOldAdvisors = $currentExternalAdvisors->map->fresh();
        $this->assertNotNull($freshOldAdvisors[0], 'first old advisor was removed');
        // modified with term, ending from today, i.e. after 10 days.
        $this->assertEquals(today(), $freshOldAdvisors[0]->ended_on);

        $this->assertNotNull($freshOldAdvisors[1], 'second old advisor was removed');
        $this->assertEquals(today(), $freshOldAdvisors[1]->ended_on);

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);
        $this->assertEquals(User::class, $advisors->first()->advisor_type);
        $this->assertEquals($facultyCosupervisor->id, $advisors->first()->advisor_id);

        // Created with new term, starting from today, i.e. after 10 days.
        $this->assertEquals(today(), $advisors->first()->started_on);
    }

    /** @test */
    public function scholar_current_advisors_can_be_replaced_by_existing_externals_as_new_current_advisors()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $currentExternalAdvisors = make(ScholarAdvisor::class, 2, ['advisor_type' => ExternalAuthority::class]);
        $scholar->advisors()->saveMany($currentExternalAdvisors);

        $existingExternal = create(ExternalAuthority::class);

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisors.replace', $scholar), [
                'advisors' => [
                    ['external_id' => $existingExternal->id],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $freshOldAdvisors = $currentExternalAdvisors->map->fresh();

        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);
        $this->assertEquals(ExternalAuthority::class, $advisors->first()->advisor_type);
        $this->assertEquals($existingExternal->id, $advisors->first()->advisor_id);

        // Created with new term, starting from today, i.e. after 10 days.
        $this->assertEquals(today(), $advisors->first()->started_on);
    }

    /** @test */
    public function scholar_current_advisors_can_be_replaced_by_new_externals_craeted_on_the_fly_and_made_current_advisors()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $currentExternalAdvisors = make(ScholarAdvisor::class, 2, ['advisor_type' => ExternalAuthority::class]);
        $scholar->advisors()->saveMany($currentExternalAdvisors);

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisors.replace', $scholar), [
                'advisors' => [
                    $external = make(ExternalAuthority::class)->attributesToArray(),
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

        $externalAuthorities = ExternalAuthority::where($external)->get();
        $this->assertCount(1, $externalAuthorities);
        $this->assertCount(1, $advisors = $scholar->refresh()->currentAdvisors);
        $this->assertEquals(ExternalAuthority::class, $advisors->first()->advisor_type);
        $this->assertEquals($externalAuthorities->first()->id, $advisors->first()->advisor_id);

        // Created with new term, starting from today, i.e. after 10 days.
        $this->assertEquals(today(), $advisors->first()->started_on);
    }

    /** @test */
    public function scholars_current_advisors_cannot_be_replaced_with_current_supervisor_cosupervisor_or_same_advisors()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->create([
            'person_type' => User::class,
            'person_id' => $cosupervisor->id,
        ]);

        $externalAdvisors = make(ScholarAdvisor::class, 2, ['advisor_type' => ExternalAuthority::class]);
        $scholar->advisors()->saveMany($externalAdvisors);

        $existingExternals = create(ExternalAuthority::class, 2);

        // 10 Days later...
        Carbon::setTestNow(today()->addDays(10));

        $this->signIn($supervisor);

        // Try adding current Supervisor and cosupervisor as advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('research.scholars.advisors.replace', $scholar), [
                    'advisors' => [
                        ['user_id' => $supervisor->id],
                        ['user_id' => $cosupervisor->id],
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0.user_id', $e->errors(), 'Supervisor was allowed');
            $this->assertArrayHasKey('advisors.1.user_id', $e->errors(), 'Cosupervisor was allowed');
        }

        $this->assertCount(2, $advisors = $scholar->refresh()->advisors);

        // Try adding current same person as two different advisors.
        try {
            $this->withoutExceptionHandling()
                ->patch(route('research.scholars.advisors.replace', $scholar), [
                    'advisors' => [
                        ['external_id' => $existingExternals[0]->id],
                        ['external_id' => $existingExternals[0]->id],
                    ],
                ])->assertRedirect()
                ->assertSessionHasFlash('success', 'Advisors Updated SuccessFully!');

            $this->fail('Advisors were allowed to same as supervisor or cosupervisor. Valdiation Error was Expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('advisors.0.external_id', $e->errors(), 'Duplicates were allowed');
            $this->assertArrayHasKey('advisors.1.external_id', $e->errors(), 'Duplicates were allowed');
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
            ->patch(route('research.scholars.advisors.replace', $scholar), [
                'advisors' => [
                    ['user_id' => $facultyCosupervisor->id],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('warning', 'There must be advisors already assigned to be replaced.');

        $this->assertCount(0, $advisors = $scholar->refresh()->currentAdvisors);
    }
}
