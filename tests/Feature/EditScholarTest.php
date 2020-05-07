<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
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
    public function cosupervisor_of_scholar_can_be_edited()
    {
        $this->signIn();

        $cosupervisor = create(Cosupervisor::class);

        $scholar = create(Scholar::class, 1, [
            'cosupervisor_profile_type' => Cosupervisor::class,
            'cosupervisor_profile_id' => $cosupervisor->id,
        ]);

        $this->assertEquals($scholar->cosupervisor_profile_id, $cosupervisor->id);

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.update', $scholar), [
                'cosupervisor_profile_type' => Cosupervisor::class,
                'cosupervisor_profile_id' => $newCosupervisorId = create(Cosupervisor::class)->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals(1, Scholar::count());
        $this->assertEquals($newCosupervisorId, $scholar->fresh()->cosupervisor_profile_id);
        $this->assertEquals('App\Models\Cosupervisor', $scholar->fresh()->cosupervisor_profile_type);

        $this->withoutExceptionHandling()
            ->patch(route('staff.scholars.update', $scholar), [
                'cosupervisor_profile_type' => SupervisorProfile::class,
                'cosupervisor_profile_id' => $newCosupervisorId = create(SupervisorProfile::class)->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals(1, Scholar::count());
        $this->assertEquals($newCosupervisorId, $scholar->fresh()->cosupervisor_profile_id);
        $this->assertEquals('App\Models\SupervisorProfile', $scholar->fresh()->cosupervisor_profile_type);
    }

    /** @test */
    public function supervisor_profile_id_of_scholar_can_be_edited()
    {
        $this->signIn();

        $supervisorProfile = create(SupervisorProfile::class);
        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);

        $this->assertEquals($scholar->supervisor_profile_id, $supervisorProfile->id);

        $this->withoutExceptionHandling()
             ->patch(route('staff.scholars.update', $scholar), [
                 'supervisor_profile_id' => $newSupervisorProfileId = create(SupervisorProfile::class)->id,
             ])
             ->assertRedirect()
             ->assertSessionHasFlash('success', 'Scholar updated successfully');

        $this->assertEquals(1, Scholar::count());
        $this->assertEquals($newSupervisorProfileId, $scholar->fresh()->supervisor_profile_id);
    }

    /** @test */
    public function cosupervisor_and_supervisor_can_not_be_same()
    {
        $this->signIn();

        $cosupervisor = create(Cosupervisor::class);
        $supervisorProfile = create(SupervisorProfile::class);

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisorProfile->id,
            'cosupervisor_profile_type' => Cosupervisor::class,
            'cosupervisor_profile_id' => $cosupervisor->id,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.scholars.update', $scholar), [
                    'supervisor_profile_id' => $supervisorProfile->id,
                    'cosupervisor_profile_type' => SupervisorProfile::class,
                    'cosupervisor_profile_id' => $supervisorProfile->id,
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('cosupervisor_profile_id', $e->errors());
        }

        $updatedScholar = $scholar->fresh();
        $this->assertEquals($cosupervisor->id, $updatedScholar->cosupervisor_profile_id);
        $this->assertEquals('App\Models\Cosupervisor', $updatedScholar->cosupervisor_profile_type);
    }
}
