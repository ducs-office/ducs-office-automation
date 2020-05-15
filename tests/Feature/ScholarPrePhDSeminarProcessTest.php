<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\ScholarDocument;
use App\Models\User;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScholarPrePhDSeminarProcessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_view_pre_phd_seminar_application_if_all_required_documents_have_been_uploaded()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::ACCEPTANCE_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', $scholar))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function scholar_can_view_pre_phd_seminar_application_if_the_scholars_phd_seminar_appeal_exists()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        create(ScholarAppeal::class, 1, [
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', $scholar))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function user_can_view_scholar_pre_phd_form_seminar_application_if_scholar_phd_seminar_appeal_exists_and_user_has_permission_to_respond_to_appeals()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar appeals:respond');

        $scholar = create(Scholar::class);

        create(ScholarAppeal::class, 1, [
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', $scholar))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function scholar_supervisor_can_view_scholars_phd_seminar_application_if_scholars_phd_seminar_appeal_exists()
    {
        $this->signIn($user = create(User::class));

        $supervisorProfile = $user->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisorProfile->id,
        ]);

        create(ScholarAppeal::class, 1, [
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', $scholar))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function scholar_can_apply_for_a_pre_phd_seminar()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::ACCEPTANCE_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertRedirect();

        $freshScholar = $scholar->fresh();

        $this->assertCount(1, $freshScholar->phdSeminarAppeal());
        $this->assertEquals(now()->format('Y-m-d'), $freshScholar->phdSeminarAppeal()->first()->applied_on->format('Y-m-d'));
        $this->assertEquals(ScholarAppealStatus::APPLIED, $freshScholar->phdSeminarAppeal()->first()->status);
    }

    /** @test */
    public function scholar_can_apply_for_a_single_pre_phd_seminar_only()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_not_apply_for_a_pre_phd_seminar_if_all_required_documents_are_not_uploaded()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function scholars_supervisor_can_recommend_their_scholars_appeals()
    {
        $this->signIn($user = create(User::class));

        $supervisorProfile = $user->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisorProfile->id,
        ]);

        $appeal = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => ScholarAppealStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.appeals.recommend', [$scholar, $appeal]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal recommended successfully!");

        $this->assertEquals(ScholarAppealStatus::RECOMMENDED, $appeal->fresh()->status);
    }

    /** @test */
    public function user_can_reject_phd_seminar_appeal_if_they_have_the_permission_to_respond_to_scholar_appeals()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar appeals:respond');

        $scholar = create(Scholar::class);

        $appeal = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => ScholarAppealStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.appeals.respond', [$scholar, $appeal]), [
                'response' => ScholarAppealStatus::REJECTED,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal rejected successfully!");

        $this->assertEquals(ScholarAppealStatus::REJECTED, $appeal->fresh()->status);
    }

    /** @test */
    public function user_can_approve_phd_seminar_appeal_if_they_have_the_permission_to_respond_to_scholar_appeals()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar appeals:respond');

        $scholar = create(Scholar::class);

        $appeal = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => ScholarAppealStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.appeals.respond', [$scholar, $appeal]), [
                'response' => ScholarAppealStatus::APPROVED,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal approved successfully!");

        $this->assertEquals(ScholarAppealStatus::APPROVED, $appeal->fresh()->status);
    }
}
