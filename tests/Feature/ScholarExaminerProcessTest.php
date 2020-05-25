<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarExaminer;
use App\Models\TitleApproval;
use App\Models\User;
use App\Types\RequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ScholarExaminerProcessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_can_request_for_examiner_of_scholar()
    {
        $supervisor = factory(User::class)->state('supervisor')->create();

        $this->signIn($supervisor);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);

        create(TitleApproval::class, 1, [
            'status' => RequestStatus::APPROVED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Applied for Scholar\'s Examiner Successfully!');

        $scholar->refresh();

        $this->assertNotNull($scholar->examiner);
        $this->assertEquals(RequestStatus::APPLIED, $scholar->examiner->status);
        $this->assertEquals(now()->format('Y-m-d'), $scholar->examiner->applied_on->format('Y-m-d'));
    }

    /** @test */
    public function scholar_can_not_apply_for_examiner()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        create(TitleApproval::class, 1, [
            'status' => RequestStatus::APPROVED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertRedirect(route('login-form'));

        $this->assertNull($scholar->examiner);
    }

    /** @test */
    public function user_who_is_not_supervisor_can_not_apply_for_examiner()
    {
        $this->signIn();

        $scholar = create(Scholar::class);

        create(TitleApproval::class, 1, [
            'status' => RequestStatus::APPROVED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertForbidden();

        $this->assertNull($scholar->examiner);
    }

    /** @test */
    public function supervisor_can_not_apply_for_examiner_of_other_supervisor_scholars()
    {
        $supervisor = factory(User::class)->state('supervisor')->create();

        $this->signIn($supervisor);

        $scholar = create(Scholar::class);
        $scholarSupervisor = factory(User::class)->state('supervisor')->create();

        $scholar->supervisors()->attach($scholarSupervisor);

        create(TitleApproval::class, 1, [
            'status' => RequestStatus::APPROVED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertForbidden();

        $this->assertNull($scholar->examiner);
    }

    /** @test */
    public function scholar_examiner_can_be_applied_only_after_scholar_title_approval_is_approved()
    {
        $supervisor = factory(User::class)->state('supervisor')->create();

        $this->signIn($supervisor);

        $scholar = create(Scholar::class);

        $scholar->supervisors()->attach($supervisor);

        $this->assertNull($scholar->titleApproval);

        $this->withExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertForbidden();

        $this->assertNull($scholar->examiner);

        $titleApproval = create(TitleApproval::class, 1, [
            'status' => RequestStatus::APPLIED,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertEquals(RequestStatus::APPLIED, $scholar->fresh()->titleApproval->status);

        $this->withExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertForbidden();

        $this->assertNull($scholar->examiner);

        $titleApproval->update(['status' => RequestStatus::RECOMMENDED]);

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->fresh()->titleApproval->status);

        $this->withExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertForbidden();

        $this->assertNull($scholar->examiner);

        $titleApproval->update(['status' => RequestStatus::APPROVED]);

        $this->assertEquals(RequestStatus::APPROVED, $scholar->fresh()->titleApproval->status);

        $this->withoutExceptionHandling()
            ->post(route('scholars.examiner.apply', $scholar))
            ->assertRedirect();
    }

    /** @test */
    public function request_for_scholar_examiner_can_be_recommended_if_user_has_permission_to_do_so()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholar examiner:recommend');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::APPLIED,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertEquals(RequestStatus::APPLIED, $scholar->examiner->status);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.examiner.recommend', [$scholar, $examiner]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Examiner request recommended successfully!');

        $scholar->refresh();

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->examiner->status);
        $this->assertEquals(now()->format('Y-m-d'), $scholar->examiner->recommended_on->format('Y-m-d'));
    }

    /** @test */
    public function request_for_scholar_examiner_can_not_be_recommended_if_user_does_not_have_permission_to_do()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->revokePermissionTo('scholar examiner:recommend');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::APPLIED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.examiner.recommend', [$scholar, $examiner]))
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $scholar->fresh()->examiner->status);
    }

    /** @test */
    public function request_for_scholar_examiner_can_not_be_recommended_by_scholar()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::APPLIED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.examiner.recommend', [$scholar, $examiner]))
            ->assertRedirect(route('login-form'));

        $this->assertEquals(RequestStatus::APPLIED, $scholar->fresh()->examiner->status);
    }

    /** @test */
    public function only_scholar_examiner_with_status_applied_can_be_recommended()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholar examiner:recommend');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::RECOMMENDED,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->examiner->status);

        $this->withExceptionHandling()
            ->patch(route('scholars.examiner.recommend', [$scholar, $examiner]))
            ->assertForbidden();

        $examiner->update(['status' => RequestStatus::APPROVED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.examiner.recommend', [$scholar, $examiner]))
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPROVED, $scholar->fresh()->examiner->status);

        $examiner->update(['status' => RequestStatus::APPLIED]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.examiner.recommend', [$scholar, $examiner]))
            ->assertRedirect();

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->fresh()->examiner->status);
    }

    /** @test */
    public function request_for_scholar_examiner_can_be_approved_if_the_user_has_permission_to_do_so()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholar examiner:approve');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::RECOMMENDED,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->examiner->status);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.examiner.approve', [$scholar, $examiner]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Examiner request approved successfully!');

        $scholar->refresh();

        $this->assertEquals(RequestStatus::APPROVED, $scholar->examiner->status);
        $this->assertEquals(now()->format('Y-m-d'), $scholar->examiner->approved_on->format('Y-m-d'));
    }

    /** @test */
    public function request_for_scholar_examiner_can_not_be_approved_if_user_does_not_have_permission_to_do()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->revokePermissionTo('scholar examiner:approve');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::RECOMMENDED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
           ->patch(route('scholars.examiner.approve', [$scholar, $examiner]))
           ->assertForbidden();

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->fresh()->examiner->status);
    }

    /** @test */
    public function request_for_scholar_examiner_can_not_be_approved_by_scholar()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::RECOMMENDED,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
           ->patch(route('scholars.examiner.approve', [$scholar, $examiner]))
           ->assertRedirect(route('login-form'));

        $this->assertEquals(RequestStatus::RECOMMENDED, $scholar->fresh()->examiner->status);
    }

    /** @test */
    public function only_scholar_examiner_with_status_recocommended_can_be_approved()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholar examiner:approve');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);

        $examiner = create(ScholarExaminer::class, 1, [
            'status' => RequestStatus::APPLIED,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertEquals(RequestStatus::APPLIED, $scholar->examiner->status);

        $this->withExceptionHandling()
           ->patch(route('scholars.examiner.approve', [$scholar, $examiner]))
           ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $scholar->fresh()->examiner->status);

        $examiner->update(['status' => RequestStatus::APPROVED]);

        $this->withExceptionHandling()
           ->patch(route('scholars.examiner.approve', [$scholar, $examiner]))
           ->assertForbidden();

        $this->assertEquals(RequestStatus::APPROVED, $scholar->fresh()->examiner->status);

        $examiner->update(['status' => RequestStatus::RECOMMENDED]);

        $this->withoutExceptionHandling()
           ->patch(route('scholars.examiner.approve', [$scholar, $examiner]))
           ->assertRedirect();

        $this->assertEquals(RequestStatus::APPROVED, $scholar->fresh()->examiner->status);
    }
}
