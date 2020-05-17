<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\ScholarDocument;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class ScholarPrePhDSeminarProcessTest extends TestCase
{
    use RefreshDatabase;

    protected function addScholarDocuments($scholar)
    {
        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::ACCEPTANCE_LETTER,
            'scholar_id' => $scholar->id,
        ]);
    }

    protected function addScholarPublications($scholar)
    {
        create(Publication::class, 1, [
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);
    }

    /** @test */
    public function scholar_can_view_their_pre_phd_seminar_application()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', $scholar))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function user_can_view_scholar_pre_phd_form_seminar_application_of_scholars_if_they_have_permission_to_mark_complete_appeals()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar appeals:mark complete');

        $scholar = create(Scholar::class);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', $scholar))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function supervisor_can_view_their_scholars_phd_seminar_application()
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
    public function supervisor_can_not_view_their_scholars_phd_seminar_application()
    {
        $this->signIn($user = create(User::class));

        $supervisorProfile = $user->supervisorProfile()->create();

        $scholar = create(Scholar::class);

        create(ScholarAppeal::class, 1, [
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_apply_for_a_pre_phd_seminar_if_no_appeal_for_a_phd_seminar_exists()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        $this->addScholarDocuments($scholar);
        $this->addScholarPublications($scholar);

        $this->withoutExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Request for Pre-PhD Seminar applied successfully!');

        $freshScholar = $scholar->fresh();

        $this->assertNotNull($freshScholar->currentPhdSeminarAppeal());
        $this->assertEquals(now()->format('d F Y'), $freshScholar->currentPhdSeminarAppeal()->applied_on);
        $this->assertEquals(ScholarAppealStatus::APPLIED, $freshScholar->currentPhdSeminarAppeal()->status);
        $this->assertEquals(ScholarAppealTypes::PRE_PHD_SEMINAR, $freshScholar->currentPhdSeminarAppeal()->type);
    }

    /** @test */
    public function scholar_can_apply_for_a_pre_phd_seminar_if_their_latest_appeal_has_been_rejected()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
            'status' => ScholarAppealStatus::REJECTED,
        ]);

        $this->addScholarDocuments($scholar);
        $this->addScholarPublications($scholar);

        Carbon::setTestNow($appealTime = now()->addDay());

        $this->withoutExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Request for Pre-PhD Seminar applied successfully!');

        $freshScholar = $scholar->fresh();

        $this->assertNotNull($freshScholar->currentPhdSeminarAppeal());
        $this->assertEquals($appealTime->format('d F Y'), $freshScholar->currentPhdSeminarAppeal()->applied_on);
        $this->assertEquals(ScholarAppealStatus::APPLIED, $freshScholar->currentPhdSeminarAppeal()->status);
        $this->assertEquals(ScholarAppealTypes::PRE_PHD_SEMINAR, $freshScholar->currentPhdSeminarAppeal()->type);
    }

    /** @test */
    public function scholar_can_not_apply_for_a_pre_phd_seminar_if_all_required_documents_are_not_uploaded()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->addScholarPublications($scholar);

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_not_apply_for_a_pre_phd_seminar_if_no_publications_exist()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->addScholarDocuments($scholar);

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function scholars_supervisor_can_reject_phd_seminar_appeal()
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
            ->patch(route('scholars.appeals.reject', [$scholar, $appeal]), [
                'response' => ScholarAppealStatus::REJECTED,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal rejected successfully!");

        $this->assertEquals(ScholarAppealStatus::REJECTED, $appeal->fresh()->status);
        $this->assertEquals($scholar->proposed_title, $appeal->fresh()->proposed_title);
    }

    /** @test */
    public function scholars_supervisor_can_approve_phd_seminar_appeal()
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
            ->patch(route('scholars.appeals.approve', [$scholar, $appeal]), [
                'response' => ScholarAppealStatus::APPROVED,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal approved successfully!");

        $this->assertEquals(ScholarAppealStatus::APPROVED, $appeal->fresh()->status);
        $this->assertEquals($scholar->proposed_title, $appeal->fresh()->proposed_title);
    }

    /** @test */
    public function user_can_mark_phd_seminar_appeal_complete_if_they_have_permission_to_mark_complete()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar appeals:mark complete');

        $scholar = create(Scholar::class);

        $appeal = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => ScholarAppealStatus::APPROVED,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.appeals.mark_complete', [$scholar, $appeal]), [
                'finalized_title' => $title = Str::random('30'),
                'title_finalized_on' => $finalizedOn = '2020-01-01',
            ]);

        $this->assertEquals(ScholarAppealStatus::COMPLETED, $appeal->fresh()->status);

        $freshScholar = $scholar->fresh();
        $this->assertEquals($title, $freshScholar->finalized_title);
        $this->assertEquals($finalizedOn, $freshScholar->title_finalized_on->format('Y-m-d'));
    }
}
