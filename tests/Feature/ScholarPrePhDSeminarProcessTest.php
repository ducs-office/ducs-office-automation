<?php

namespace Tests\Feature;

use App\Models\PhdCourse;
use App\Models\PrePhdSeminar;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\ScholarDocument;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Types\PublicationType;
use App\Types\RequestStatus;
use App\Types\ScholarAppealTypes;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
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
    }

    protected function addScholarPublications($scholar)
    {
        create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);
    }

    /** @test */
    public function scholar_can_request_for_pre_phd_seminar()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $course = create(PhdCourse::class);

        $scholar->addCourse($course, [
            'completed_on' => $completeDate = now()->format('Y-m-d'),
        ]);

        $this->addScholarDocuments($scholar);
        $this->addScholarPublications($scholar);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.request', $scholar))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function scholar_can_not_request_or_for_pre_phd_seminar_if_they_have_applied_before()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $course = create(PhdCourse::class);

        $scholar->addCourse($course, [
            'completed_on' => $completeDate = now()->format('Y-m-d'),
        ]);

        $this->addScholarDocuments($scholar);
        $this->addScholarPublications($scholar);

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.request', $scholar))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function some_other_user_can_not_request_or_apply_for_pre_phd_seminar_for_other_scholar()
    {
        $this->signInScholar(create(Scholar::class));

        $scholar = create(Scholar::class);

        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $course = create(PhdCourse::class);

        $scholar->addCourse($course, [
            'completed_on' => $completeDate = now()->format('Y-m-d'),
        ]);

        $this->addScholarDocuments($scholar);
        $this->addScholarPublications($scholar);

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.request', $scholar))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertForbidden();

        $this->signInScholar(create(User::class));

        $this->withExceptionHandling()
        ->get(route('scholars.pre_phd_seminar.request', $scholar))
        ->assertForbidden();

        $this->withExceptionHandling()
        ->post(route('scholars.pre_phd_seminar.apply', $scholar))
        ->assertForbidden();
    }

    /** @test */
    public function scholar_can_not_request_or_apply_for_a_pre_phd_seminar_if_they_can_not_apply_for_pre_phd_seminar()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->addScholarPublications($scholar);

        $this->assertFalse($scholar->canApplyForPrePhdSeminar());

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.request', $scholar))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertForbidden();
    }

    /** @test */
    public function use_can_not_request_or_apply_for_a_pre_phd_seminar()
    {
        $this->signIn($user = create(User::class));

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.request', $user))
            ->assertRedirect(route('login-form'));

        $this->withExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $user))
            ->assertRedirect(route('login-form'));
    }

    /** @test */
    public function scholar_can_view_their_pre_phd_seminar_application()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function user_can_view_scholar_pre_phd_form_seminar_application_of_scholars_if_they_have_permission_to_finalize_appeals()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('phd seminar:finalize');

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
            ->assertViewIs('research.scholars.pre_phd_form');

        $user->roles->first()->revokePermissionTo('phd seminar:finalize');

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
            ->assertForbidden();
    }

    /** @test */
    public function user_can_view_scholar_pre_phd_form_seminar_application_of_scholars_if_they_have_permission_to_add_schedule_to_appeals()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('phd seminar:add schedule');

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
             ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
             ->assertViewIs('research.scholars.pre_phd_form');

        $user->roles->first()->revokePermissionTo('phd seminar:add schedule');

        $this->withExceptionHandling()
             ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
             ->assertForbidden();
    }

    /** @test */
    public function supervisor_can_view_their_scholars_phd_seminar_application()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function supervisor_can_not_view_other_scholars_phd_seminar_application()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
            ->assertForbidden();
    }

    /** @test */
    public function pre_phd_application_can_not_be_viewed_if_scholar_has_not_applied_for_it()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $prePhdSeminar = create(PrePhdSeminar::class);

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $prePhdSeminar]))
            ->assertForbidden();

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $appeal]))
            ->assertViewIs('research.scholars.pre_phd_form');
    }

    /** @test */
    public function scholar_can_not_view_others_pre_phd_seminar_appeal()
    {
        $this->signInScholar($scholar = create(Scholar::class));
        $scholar->supervisors()->attach(
            factory(User::class)->states('supervisor')->create()
        );

        $scholarAppeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $prePhdSeminar = create(PrePhdSeminar::class);

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$scholar, $prePhdSeminar]))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$prePhdSeminar->scholar, $prePhdSeminar]))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->get(route('scholars.pre_phd_seminar.show', [$prePhdSeminar->scholar, $scholarAppeal]))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_apply_for_a_pre_phd_seminar_if_no_appeal_for_a_phd_seminar_exists()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $course = create(PhdCourse::class);

        $scholar->addCourse($course, [
            'completed_on' => $completeDate = now()->format('Y-m-d'),
        ]);

        $this->addScholarDocuments($scholar);
        $this->addScholarPublications($scholar);

        $this->assertNull($scholar->prePhdSeminar);

        $this->withoutExceptionHandling()
            ->post(route('scholars.pre_phd_seminar.apply', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Request for Pre-PhD Seminar applied successfully!');

        $freshScholar = $scholar->fresh();

        $this->assertNotNull($freshScholar->prePhdSeminar);
        $this->assertEquals(now()->format('d F Y'), $freshScholar->prePhdSeminar->applied_on);
        $this->assertEquals(RequestStatus::APPLIED, $freshScholar->prePhdSeminar->status);
    }

    /** @test */
    public function scholars_supervisor_can_recommend_phd_seminar_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.forward', [$scholar, $appeal]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Scholar's appeal forwarded successfully!");

        $appeal->refresh();

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->status);
    }

    /** @test */
    public function supervisor_can_not_recommend_other_scholar_phd_seminar_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.forward', [$scholar, $appeal]))
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $appeal->status);

        $scholar->supervisors()->attach($user);

        $appeal->update(['scholar_id' => create(Scholar::class)->id]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.forward', [$scholar, $appeal]))
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $appeal->status);
    }

    /** @test */
    public function supervisor_can_not_recommend_scholar_pre_phd_seminar_appeal_if_its_status_is_recommended_or_approved()
    {
        $user = factory(User::class)->states('supervisor')->create();
        $this->signIn($user);

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.forward', [$scholar, $appeal]))
            ->assertForbidden();

        $appeal->refresh();

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->status);

        $appeal->update(['status' => RequestStatus::APPROVED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.forward', [$scholar, $appeal]))
            ->assertForbidden();

        $appeal->refresh();

        $this->assertEquals(RequestStatus::APPROVED, $appeal->status);
    }

    /** @test */
    public function scholar_can_not_recommend_their_pre_phd_seminar_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $this->signInScholar($scholar);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.forward', [$scholar, $appeal]))
            ->assertRedirect(route('login-form'));

        $this->assertEquals(RequestStatus::APPLIED, $appeal->status);
    }

    /** @test */
    public function user_can_add_scheduled_date_and_time_of_pre_phd_seminar_of_scholar_if_they_have_permission_to_do_so()
    {
        $role = Role::create(['name' => 'randomRole']);

        $role->revokePermissionTo('phd seminar:add schedule');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);
        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => null,
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.schedule', [$scholar, $appeal]), [
                'scheduled_on' => now()->addMonth(),
            ])
            ->assertForbidden();

        $role->givePermissionTo('phd seminar:add schedule');
        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);
        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => null,
            'status' => RequestStatus::RECOMMENDED,
        ]);
        $this->withoutExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.schedule', [$scholar, $appeal]), [
                'scheduled_on' => $schedule = now()->addMonth(),
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Pre PhD seminar schedule added successfully!');

        $this->assertEquals($schedule->format('Y-m-d H:m:s'), $appeal->fresh()->scheduled_on->format('Y-m-d H:m:s'));
    }

    /** @test */
    public function user_can_not_add_scheduled_date_and_time_of_pre_phd_seminar_of_scholar_if_it_is_already_set()
    {
        $role = Role::create(['name' => 'randomRole']);
        $role->givePermissionTo('phd seminar:add schedule');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);
        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => $schedule = now()->addMonth(),
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.schedule', [$scholar, $appeal]), [
                'scheduled_on' => $newSchedule = now()->addMonth(),
            ])
            ->assertForbidden();

        $this->assertEquals($schedule->format('Y-m-d H:m:s'), $appeal->fresh()->scheduled_on->format('Y-m-d H:m:s'));
    }

    /** @test */
    public function user_can_not_add_scheduled_date_and_time_of_pre_phd_seminar_of_scholar_if_its_status_is_either_applied_or_approved()
    {
        $role = Role::create(['name' => 'randomRole']);
        $role->givePermissionTo('phd seminar:add schedule');

        $this->signIn(create(User::class), $role->name);

        $scholar = create(Scholar::class);
        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.schedule', [$scholar, $appeal]), [
                'scheduled_on' => $newSchedule = now()->addMonth(),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $appeal->fresh()->status);

        $appeal->update(['status' => RequestStatus::APPROVED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.schedule', [$scholar, $appeal]), [
                'scheduled_on' => $newSchedule = now()->addMonth(),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPROVED, $appeal->fresh()->status);
    }

    /** @test */
    public function scholar_can_not_add_scheduled_date_of_pre_phd_seminar_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $this->signInScholar($scholar);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => null,
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
             ->patch(route('scholars.pre_phd_seminar.schedule', [$scholar, $appeal]), [
                 'scheduled_on' => now()->addMonth(),
             ])
             ->assertRedirect(route('login-form'));

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->status);
    }

    /** @test */
    public function user_can_finalize_pre_phd_seminar_appeal_if_they_have_permission_to_finalize()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->revokePermissionTo('phd seminar:finalize');

        $scholar = create(Scholar::class);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => now()->addMonth(),
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.finalize', [$scholar, $appeal]), [
                'finalized_title' => $title = Str::random(30),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->fresh()->status);

        $user->roles->first()->givePermissionTo('phd seminar:finalize');

        $this->withoutExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.finalize', [$scholar, $appeal]), [
                'finalized_title' => $title = Str::random(30),
            ]);

        $appeal->refresh();

        $this->assertEquals(RequestStatus::APPROVED, $appeal->status);
        $this->assertEquals($title, $appeal->finalized_title);
    }

    /** @test */
    public function user_can_not_finalize_pre_phd_seminar_appeal_if_its_status_is_either_applied_or_approved()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('phd seminar:finalize');

        $scholar = create(Scholar::class);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => now()->addMonth(),
            'status' => RequestStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.finalize', [$scholar, $appeal]), [
                'finalized_title' => $title = Str::random(30),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPLIED, $appeal->fresh()->status);

        $appeal->update(['status' => RequestStatus::APPROVED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.finalize', [$scholar, $appeal]), [
                'finalized_title' => $title = Str::random(30),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::APPROVED, $appeal->fresh()->status);
    }

    /** @test */
    public function user_can_not_finalize_pre_phd_seminar_appeal_if_its_scheduled_on_date_is_not_set()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('phd seminar:finalize');

        $scholar = create(Scholar::class);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => null,
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.finalize', [$scholar, $appeal]), [
                'finalized_title' => $title = Str::random(30),
            ])
            ->assertForbidden();

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->fresh()->status);
    }

    /** @test */
    public function scholar_can_not_finalize_their_pre_phd_seminar_appeal()
    {
        $user = factory(User::class)->states('supervisor')->create();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($user);

        $this->signInScholar($scholar);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'scheduled_on' => now()->addMonth(),
            'status' => RequestStatus::RECOMMENDED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.pre_phd_seminar.finalize', [$scholar, $appeal]), [
                'finalized_title' => Str::random(30),
            ])
            ->assertRedirect(route('login-form'));

        $this->assertEquals(RequestStatus::RECOMMENDED, $appeal->status);
    }
}
