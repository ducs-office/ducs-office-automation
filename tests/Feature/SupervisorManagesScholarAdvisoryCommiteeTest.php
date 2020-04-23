<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupervisorManagesScholarAdvisoryCommiteeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_advisory_committee_can_be_edited_by_their_supervisor()
    {
        $this->signIn($faculty = create(User::class, 1, ['category' => 'faculty_teacher']));

        $supervisor = $faculty->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisor->id,
        ]);

        $faculty_teacher = create(User::class, 1, ['category' => 'faculty_teacher']);

        $this->withoutExceptionHandling()
            ->patch(route('research.scholars.advisory_committee.update', [
                'scholar' => $scholar,
            ]), [
                'faculty_teacher' => $faculty_teacher->name,
                'external' => $external = [
                    'name' => 'Rakesh Sharma',
                    'designation' => 'astronaut',
                    'affiliation' => 'IAF',
                    'email' => 'rakesh@gmail.com',
                    'phone_no' => '9469297632',
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisory Committee Updated SuccessFully!');

        $this->assertEquals($scholar->fresh()->advisory_committee['faculty_teacher'], $faculty_teacher->name);
        $this->assertEquals($scholar->fresh()->advisory_committee['external'], $external);
    }

    /** @test */
    public function scholar_advisory_committee_can_be_replaced_by_their_supervisor()
    {
        $this->signIn($faculty = create(User::class, 1, ['category' => 'faculty_teacher']));

        $supervisor = $faculty->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisor->id,
        ]);

        $beforeReplaceAdvisoryCommittee = $scholar->advisory_committee;

        $this->assertEquals(count($scholar->old_advisory_committees), 0);

        $faculty_teacher = create(User::class, 1, ['category' => 'faculty_teacher']);

        $this->withoutExceptionHandling()
            ->post(route('research.scholars.advisory_committee.replace', [
                'scholar' => $scholar,
            ]), [
                'faculty_teacher' => $faculty_teacher->name,
                'external' => $external = [
                    'name' => 'Rakesh Sharma',
                    'designation' => 'astronaut',
                    'affiliation' => 'IAF',
                    'email' => 'rakesh@gmail.com',
                    'phone_no' => '9469297632',
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Advisory Committee Replaced SuccessFully!');

        $this->assertEquals(count($scholar->fresh()->old_advisory_committees), 1);
        $this->assertEquals($scholar->fresh()->old_advisory_committees[0]['faculty_teacher'], $beforeReplaceAdvisoryCommittee['faculty_teacher']);
        $this->assertEquals($scholar->fresh()->old_advisory_committees[0]['external'], $beforeReplaceAdvisoryCommittee['external']);
        $this->assertEquals($scholar->fresh()->old_advisory_committees[0]['date'], now()->format('d F Y'));

        $this->assertEquals($scholar->fresh()->advisory_committee['faculty_teacher'], $faculty_teacher->name);
        $this->assertEquals($scholar->fresh()->advisory_committee['external'], $external);
    }

    // /** @test */
    // public function scholar_advisory_committee_update_requires_atleast_one_of_external_or_faculty_teacher()
    // {

    //     // validate empty
    //     $this->signIn($faculty = create(User::class, 1, ['category' => 'faculty_teacher']));

    //     $supervisor = $faculty->supervisorProfile()->create();

    //     $scholar = create(Scholar::class, 1, [
    //         'supervisor_profile_id' => $supervisor->id
    //     ]);

    //     try {
    //         $this->withoutExceptionHandling()
    //             ->patch(route('research.scholars.advisory_committee.update', [
    //                 'scholar' => $scholar
    //             ]),[]);

    //         $this->fail('Ateast one of external or faculty teacher required was not validated');
    //     }catch(ValidationException $e) {
    //         $this->assertArrayHasKey('faculty_teacher', $e->errors());
    //         $this->assertArrayHasKey('external', $e->errors());
    //     } catch (Exception $e) {
    //         $this->fail($e->getMessage());
    //     }

    //     //validate only external

    //     $this->withoutExceptionHandling()
    //         ->patch(route('research.scholars.advisory_committee.update', [
    //             'scholar' => $scholar
    //         ]),[
    //             'external' => $external = [
    //                 'name' => 'Rakesh Sharma',
    //                 'designation' => 'astronaut',
    //                 'affiliation' => 'IAF',
    //                 'email' => 'rakesh@gmail.com',
    //                 'phone_no' => '9469297632',
    //             ]
    //         ])->assertRedirect()
    //         ->assertSessionHasFlash('success', 'Advisory Committee Updated SuccessFully!');

    //         $this->assertEquals($scholar->fresh()->advisory_committee['external'], $external);

    //         //validate only faculty teacher

    //         $faculty_teacher = create(User::class, 1, ['category' => 'faculty_teacher']);

    //         $this->withoutExceptionHandling()
    //         ->patch(route('research.scholars.advisory_committee.update', [
    //             'scholar' => $scholar
    //         ]),[
    //             'faculty_teacher' => $faculty_teacher->name,
    //         ])->assertRedirect()
    //         ->assertSessionHasFlash('success', 'Advisory Committee Updated SuccessFully!');

    //         $this->assertEquals($scholar->fresh()->advisory_committee['faculty_teacher'], $faculty_teacher->name);
    // }
}
