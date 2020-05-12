<?php

namespace Tests\Feature;

use App\Mail\FillAdvisoryCommitteeMail;
use App\Mail\UserRegisteredMail;
use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateNewScholarTest extends TestCase
{
    use RefreshDatabase;

    protected function getScholarFormDetails($overrides = [])
    {
        return $this->mergeFormFields([
            'first_name' => 'Pushkar',
            'last_name' => 'Sonkar',
            'email' => 'pushkar@cs.du.ac.in',
            'term_duration' => 5,
            'supervisor_id' => function () {
                return factory(User::class)->states('supervisor')->create()->id;
            },
            'cosupervisor_id' => function () {
                return create(Cosupervisor::class)->id;
            },
        ], $overrides);
    }

    /** @test */
    public function scholars_can_not_be_created_by_a_guest()
    {
        $this->expectException(AuthenticationException::class);

        $this->withoutExceptionHandling()
            ->post(route('staff.scholars.store'));

        $this->assertEquals(0, Scholar::count());
    }

    /** @test */
    public function scholar_created_with_supervisor_and_cosupervisor_and_mail_is_sent_to_scholar_and_supervisor()
    {
        $this->signIn();

        $supervisor = factory(User::class)->states('supervisor')->create();
        $cosupervisor = factory(Cosupervisor::class)->create();

        $scholarParam = $this->getScholarFormDetails([
            'cosupervisor_id' => $cosupervisor->id,
            'supervisor_id' => $supervisor->id,
        ]);

        Mail::fake();

        $this->withExceptionHandling()
            ->post(route('staff.scholars.store'), $scholarParam)
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', 'New scholar added succesfully!');

        $scholars = Scholar::query()
            ->where('first_name', $scholarParam['first_name'])
            ->where('last_name', $scholarParam['last_name'])
            ->where('email', $scholarParam['email'])
            ->where('term_duration', $scholarParam['term_duration'])
            ->get();

        $this->assertCount(1, $scholars);

        $newSupervisors = $scholars->first()
            ->supervisors()
            ->wherePivot('supervisor_id', $supervisor->id)
            ->wherePivot('started_on', today())
            ->wherePivot('ended_on', null)
            ->get();

        $this->assertCount(1, $newSupervisors);

        $cosupervisors = $scholars->first()
            ->cosupervisors()
            ->wherePivot('cosupervisor_id', $cosupervisor->id)
            ->wherePivot('started_on', today())
            ->whereNull('ended_on')
            ->get();

        $this->assertCount(1, $cosupervisors);

        Mail::assertQueued(UserRegisteredMail::class, function ($mail) use ($scholars) {
            $data = $mail->build()->viewData;
            $this->assertArrayHasKey('user', $data);
            $this->assertArrayHasKey('password', $data);
            return (int) $data['user']->id === (int) $scholars->first()->id;
        });

        Mail::assertQueued(FillAdvisoryCommitteeMail::class, function ($mail) use ($supervisor) {
            $data = $mail->build()->viewData;
            $this->assertArrayHasKey('supervisor', $data);
            $this->assertArrayHasKey('scholarName', $data);
            $this->assertArrayHasKey('deadline', $data);
            return (int) $data['supervisor']->id === (int) $supervisor->id;
        });
    }

    /** @test */
    public function request_validates_first_name_and_last_name_are_required()
    {
        $this->signIn();

        $scholar = $this->getScholarFormDetails([
            'first_name' => '',
            'last_name' => '',
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.scholars.store'), $scholar);
            $this->fail('Validation exception was expected');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('first_name', $e->errors());
            $this->assertArrayHasKey('last_name', $e->errors());
        }

        $this->assertEquals(0, Scholar::count());
    }

    /** @test */
    public function request_validates_email_is_unique()
    {
        $this->signIn();

        $otherScholar = create(Scholar::class);

        $scholar = $this->getScholarFormDetails([
            'email' => $otherScholar->email,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.scholars.store'), $scholar);
            $this->fail('Validation exception was expected');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }

        $this->assertEquals(1, Scholar::count());
    }

    /** @test */
    public function request_validates_cosupervisor_id_is_optional()
    {
        $this->signIn();

        $scholar = $this->getScholarFormDetails([
            'cosupervisor_id' => null,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('staff.scholars.store'), $scholar)
            ->assertSessionHasNoErrors();

        $this->assertNull(Scholar::first()->cosupervisor);
    }

    /** @test */
    public function request_validates_cosupervisor_and_supervisor_can_not_be_same()
    {
        $this->signIn();

        $supervisor = factory(User::class)->states('supervisor')->create();
        $cosupervisorWhoIsSupervisor = create(Cosupervisor::class, 1, [
            'person_type' => User::class,
            'person_id' => $supervisor->id,
        ]);

        $scholar = $this->getScholarFormDetails([
            'supervisor_id' => $supervisor->id,
            'cosupervisor_id' => $cosupervisorWhoIsSupervisor->id,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.scholars.store'), $scholar);
            $this->fail('Validation exception was expected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('cosupervisor_id', $e->errors());
        }

        $this->assertEquals(0, Scholar::count());
    }

    /** @test */
    public function request_validates_supervisor_id_is_required()
    {
        $this->signIn();

        $cosupervisor = create(Cosupervisor::class);

        $this->withExceptionHandling()
            ->post(route('staff.scholars.store'), [
                'first_name' => 'Pushkar',
                'last_name' => 'Sonkar',
                'email' => 'pushkar@cs.du.ac.in',
                'cosupervisor_id' => $cosupervisor->id,
            ])
            ->assertSessionHasErrors('supervisor_id');

        $this->assertEquals(0, Scholar::count());
    }
}
