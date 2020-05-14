<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\ScholarCosupervisor;
use App\Models\User;
use Dotenv\Regex\Success;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EditScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_of_scholar_can_be_edited()
    {
        $this->signIn();

        $email = 'scholar@gmail.com';
        $scholar = create(Scholar::class, 1, ['email' => $email]);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->withoutExceptionHandling()
           ->patch(route('staff.scholars.update', $scholar), [
               'email' => $newEmail = 'scholar.du.ac.in',
           ])
           ->assertRedirect()
           ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newEmail, $scholar->fresh()->email);
    }

    /** @test */
    public function first_name_of_scholar_can_be_edited()
    {
        $this->signIn();

        $firstName = 'Pushcar';
        $scholar = create(Scholar::class, 1, ['first_name' => $firstName]);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->withoutExceptionHandling()
           ->patch(route('staff.scholars.update', $scholar), [
               'first_name' => $newFirstName = 'Pushkar',
           ])
           ->assertRedirect()
           ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newFirstName, $scholar->fresh()->first_name);
    }

    /** @test */
    public function last_name_of_scholar_can_be_edited()
    {
        $this->signIn();

        $lastName = 'Solanki';
        $scholar = create(Scholar::class, 1, ['last_name' => $lastName]);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->withoutExceptionHandling()
           ->patch(route('staff.scholars.update', $scholar), [
               'last_name' => $newLastName = 'Sonkar',
           ])
           ->assertRedirect()
           ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newLastName, $scholar->fresh()->last_name);
    }

    /** @test */
    public function scholar_is_not_validated_for_uniqueness_if_email_is_not_changed()
    {
        $this->signIn();

        $lastName = 'Solanki';
        $scholar = create(Scholar::class, 1, ['last_name' => $lastName]);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.update', $scholar), [
                'first_name' => $scholar->first_name,
                'last_name' => $newLastName = 'Sonkar',
                'email' => $scholar->email,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals($newLastName, $scholar->fresh()->last_name);
        $this->assertEquals(1, Scholar::count());
    }

    /** @test */
    public function cosupervisor_of_scholar_can_be_edited_without_tracking()
    {
        $this->signIn();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );
        $oldCosupervisorPrevious = factory(User::class)->states('cosupervisor')->create();
        $oldCosupervisorCurrent = factory(User::class)->states('cosupervisor')->create();
        $newCosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->createMany([
            [
                'person_type' => User::class,
                'person_id' => $oldCosupervisorPrevious->id,
                'started_on' => today()->subMonths(10),
                'ended_on' => today()->subMonths(2),
            ],
            [
                'person_type' => User::class,
                'person_id' => $oldCosupervisorCurrent->id,
                'started_on' => today()->subMonths(2),
                'ended_on' => null,
            ],
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.update', $scholar), [
                'cosupervisor_user_id' => $newCosupervisor->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertCount(2, $scholar->refresh()->cosupervisors);
        $this->assertEquals($newCosupervisor->id, $scholar->currentCosupervisor->person_id);
        $this->assertEquals(today()->subMonths(2), $scholar->currentCosupervisor->started_on);
        $this->assertTrue($oldCosupervisorPrevious->exists());
    }

    /** @test */
    public function supervisor_of_scholar_can_be_updated_without_tracking()
    {
        $this->signIn();

        $oldSupervisors = factory(User::class, 2)->states('supervisor')->create();
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach([
            $oldSupervisors[0]->id => ['started_on' => today()->subMonths(10), 'ended_on' => today()->subMonths(2)],
            $oldSupervisors[1]->id => ['started_on' => today()->subMonths(2), 'ended_on' => null],
        ]);

        $newSupervisor = factory(User::class)->states('supervisor')->create();
        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.update', $scholar), [
                'supervisor_id' => $newSupervisor->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals(1, Scholar::count());
        $this->assertCount(2, $scholar->refresh()->supervisors);
        $this->assertEquals($newSupervisor->id, $scholar->currentSupervisor->id);
        $this->assertEquals(today()->subMonths(2), $scholar->currentSupervisor->pivot->started_on);
    }

    /** @test */
    public function cosupervisor_and_supervisor_can_not_be_same()
    {
        $this->signIn();

        $scholar = create(Scholar::class);
        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);
        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->create([
            'person_type' => User::class,
            'person_id' => $cosupervisor->id,
        ]);

        $this->withExceptionHandling()
            ->patch(route('staff.scholars.update', $scholar), [
                'cosupervisor_user_id' => $supervisor->id,
            ])
            ->assertSessionHasErrors('cosupervisor_user_id');

        $updatedScholar = $scholar->fresh();
        $this->assertEquals($cosupervisor->id, $updatedScholar->currentCosupervisor->person_id);
    }
}
