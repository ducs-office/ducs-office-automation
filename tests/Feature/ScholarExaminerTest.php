<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\User;
use App\Types\RequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ScholarExaminerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_can_request_for_examiner_of_scholar()
    {
        $supervisor = factory(User::class)->state('supervisor')->create();

        $this->signIn($supervisor);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);

        $this->assertNull($scholar->examiner_status);
        $this->assertNull($scholar->examiner_applied_on);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.examiner.apply', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Applied for Scholar\'s Examiner Successfully!');

        $scholar->refresh();

        $this->assertEquals(RequestStatus::APPLIED, $scholar->examiner_status);
        $this->assertEquals(now()->format('Y-m-d'), $scholar->examiner_applied_on->format('Y-m-d'));
    }

    /** @test */
    public function request_for_scholar_examiner_can_be_recommended_if_user_has_permission_to_do_so()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholar examiner:recommend');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class, 1, [
            'examiner_status' => RequestStatus::APPLIED,
        ]);

        $this->assertEquals(RequestStatus::APPLIED, $scholar->examiner_status);
        $this->assertNUll($scholar->examiner_recommended_on);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.examiner.recommend', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Examiner request recommended successfully!');

        $scholar->refresh();

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->examiner_status);
        $this->assertEquals(now()->format('Y-m-d'), $scholar->examiner_recommended_on->format('Y-m-d'));
    }

    /** @test */
    public function request_for_scholar_examiner_can_be_approved_if_the_user_has_permission_to_do_so()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholar examiner:approve');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class, 1, [
            'examiner_status' => RequestStatus::RECOMMENDED,
        ]);

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->examiner_status);
        $this->assertNUll($scholar->examiner_approved_on);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.examiner.approve', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Examiner request approved successfully!');

        $scholar->refresh();

        $this->assertEquals(RequestStatus::APPROVED, $scholar->examiner_status);
        $this->assertEquals(now()->format('Y-m-d'), $scholar->examiner_approved_on->format('Y-m-d'));
    }
}
