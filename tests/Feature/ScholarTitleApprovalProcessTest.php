<?php

namespace Tests\Feature;

use App\Models\PrePhdSeminar;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\TitleApproval;
use App\Models\User;
use App\Types\PrePhdCourseType;
use App\Types\RequestStatus;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ScholarTitleApprovalProcessTest extends TestCase
{
    use RefreshDatabase;

    protected function addScholarDocuments($scholar)
    {
        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::THESIS_TOC,
            'scholar_id' => $scholar->id,
        ]);

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::PRE_PHD_SEMINAR_NOTICE,
            'scholar_id' => $scholar->id,
        ]);
    }

    protected function addPrePhdRequest($scholar)
    {
        create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar,
            'status' => RequestStatus::APPROVED,
        ]);
    }

    /** @test */
    public function scholar_can_request_for_title_approval()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->addScholarDocuments($scholar);
        $this->addPrePhdRequest($scholar);

        $this->withoutExceptionHandling()
            ->get(route('scholars.title-approval.request', $scholar))
            ->assertViewIs('title-approval.form');
    }

    /** @test */
    public function scholar_can_not_request_or_apply_for_title_approval_if_they_have_applied_before()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->addScholarDocuments($scholar);
        $this->addPrePhdRequest($scholar);

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.request', $scholar))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('scholars.title-approval.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function some_other_user_can_not_request_or_apply_for_title_approval_for_other_scholar()
    {
        $this->signInScholar(create(Scholar::class));

        $scholar = create(Scholar::class);

        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->addPrePhdRequest($scholar);
        $this->addScholarDocuments($scholar);

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.request', $scholar))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('scholars.title-approval.apply', $scholar))
            ->assertForbidden();

        $this->signInScholar(create(User::class));

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.request', $scholar))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('scholars.title-approval.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_not_request_or_apply_for_a_title_approval_if_they_can_not_apply_for_title_approval()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->addPrePhdRequest($scholar);

        $this->assertFalse($scholar->canApplyForTitleApproval());

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.request', $scholar))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('scholars.title-approval.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_view_their_title_approval_application()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->addPrePhdRequest($scholar);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $appeal]))
            ->assertViewIs('title-approval.form');
    }

    /** @test */
    public function user_can_view_scholar_title_approval_form_seminar_application_of_scholars_if_they_have_permission_to_approve()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('title approval:approve');

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->addPrePhdRequest($scholar);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $appeal]))
            ->assertViewIs('title-approval.form');

        $user->roles->first()->revokePermissionTo('title approval:approve');

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $appeal]))
            ->assertForbidden();
    }

    /** @test */
    public function supervisor_can_view_their_scholars_title_approval_application()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $this->addPrePhdRequest($scholar);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $appeal]))
            ->assertViewIs('title-approval.form');
    }

    /** @test */
    public function supervisor_can_not_view_other_scholars_title_approval_application()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $this->addPrePhdRequest($scholar);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $appeal]))
            ->assertForbidden();
    }

    /** @test */
    public function title_approval_application_can_not_be_viewed_if_scholar_has_not_applied_for_it()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $this->addPrePhdRequest($scholar);

        $titleApproval = create(TitleApproval::class);

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $titleApproval]))
            ->assertForbidden();

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $appeal]))
            ->assertViewIs('title-approval.form');
    }

    /** @test */
    public function scholar_can_not_view_others_title_approval_appeal()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );
        $this->addPrePhdRequest($scholar);

        $scholarAppeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $appeal = create(TitleApproval::class);

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.show', [$scholar, $appeal]))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.show', [$appeal->scholar, $appeal]))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->get(route('scholars.title-approval.show', [$appeal->scholar, $scholarAppeal]))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_apply_for_a_title_approval_if_no_appeal_for_a_title_approval_exists()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->addScholarDocuments($scholar);
        $this->addPrePhdRequest($scholar);

        $this->assertNull($scholar->titleApproval);

        $this->withoutExceptionHandling()
            ->post(route('scholars.title-approval.apply', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Request for Title Approval applied successfully!');

        $freshScholar = $scholar->fresh();

        $this->assertNotNull($freshScholar->titleApproval);
        $this->assertEquals(now()->format('d F Y'), $freshScholar->titleApproval->applied_on);
        $this->assertEquals(RequestStatus::APPLIED, $freshScholar->titleApproval->status);
    }

    /** @test */
    public function scholars_supervisor_can_recommend_title_approval_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.title-approval.recommend', [$scholar, $appeal]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal recommended successfully!");

        $appeal->refresh();

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->status);
    }

    /** @test */
    public function supervisor_can_not_recommend_other_scholar_title_approval_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.recommend', [$scholar, $appeal]))
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $appeal->status);

        $scholar->supervisors()->attach($user);

        $appeal->update(['scholar_id' => create(Scholar::class)->id]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.recommend', [$scholar, $appeal]))
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $appeal->status);
    }

    /** @test */
    public function scholar_can_not_recommend_their_title_approval_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $this->signInScholar($scholar);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.recommend', [$scholar, $appeal]))
            ->assertRedirect(route('login-form'));

        $this->assertEquals(RequestStatus::APPLIED, $appeal->status);
    }

    /** @test */
    public function supervisor_can_not_recommend_scholar_title_approval_appeal_if_its_status_is_recommended_or_approved()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.recommend', [$scholar, $appeal]))
            ->assertForbidden();

        $appeal->refresh();

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->status);

        $appeal->update(['status' => RequestStatus::APPROVED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.recommend', [$scholar, $appeal]))
            ->assertForbidden();

        $appeal->refresh();

        $this->assertEquals(RequestStatus::APPROVED, $appeal->status);
    }

    /** @test */
    public function user_can_approve_title_approval_appeal_if_they_have_permission_to_approve()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->revokePermissionTo('title approval:approve');

        $scholar = create(Scholar::class);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.approve', [$scholar, $appeal]))
            ->assertForbidden();

        $user->roles->first()->givePermissionTo('title approval:approve');

        $this->withoutExceptionHandling()
            ->patch(route('scholars.title-approval.approve', [$scholar, $appeal]), [
                'recommended_title' => $title = Str::random('30'),
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal approved successfully!");

        $appeal->refresh();

        $this->assertEquals(RequestStatus::APPROVED, $appeal->status);
        $this->assertEquals($title, $appeal->recommended_title);
    }

    /** @test */
    public function user_can_not_approve_title_approval_appeal_if_its_status_is_either_applied_or_approved()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('title approval:approve');

        $scholar = create(Scholar::class);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.approve', [$scholar, $appeal]), [
                'recommended_title' => $title = Str::random('30'),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $appeal->fresh()->status);

        $appeal->update(['status' => RequestStatus::APPROVED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.approve', [$scholar, $appeal]), [
                'recommended_title' => $title = Str::random('30'),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPROVED, $appeal->fresh()->status);
    }

    /** @test */
    public function scholar_can_not_approve_their_title_approval_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $this->signInScholar($scholar);

        $appeal = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.title-approval.approve', [$scholar, $appeal]))
            ->assertRedirect(route('login-form'));

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->status);
    }
}
