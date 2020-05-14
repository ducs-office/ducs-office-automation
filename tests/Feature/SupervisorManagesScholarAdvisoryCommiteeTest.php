<?php

namespace Tests\Feature;

use App\ExternalAuthority;
use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\ScholarAdvisor;
use App\Models\ScholarCosupervisor;
use App\Models\User;
use App\Types\AdvisoryCommitteeMember;
use App\Types\Designation;
use App\Types\UserCategory;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function scholar_advisors_cannot_be_replaced_if_no_current_advisors_assigned()
    {
        $scholar = create(Scholar::class);

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->make();
        $scholar->cosupervisors()->create($cosupervisor->attributesToArray());

        $this->signIn($supervisor);

        $this->withExceptionHandling()
            ->patch(route('research.scholars.advisors.replace', $scholar))
            ->assertForbidden();

        $this->assertCount(0, $advisors = $scholar->refresh()->currentAdvisors);
    }
}
