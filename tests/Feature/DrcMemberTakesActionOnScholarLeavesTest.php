<?php

namespace Tests\Feature;

use App\Models\Leave;
use App\Models\Scholar;
use App\Models\User;
use App\Types\LeaveStatus;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class DrcMemberTakesActionOnScholarLeavesTest extends TestCase
{
    use RefreshDatabase;

    protected $responseLetter;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $this->responseLetter = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf');
    }

    /** @test */
    public function user_can_approve_leaves_when_they_have_permission_to_respond()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('leaves:respond');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.leaves.respond', [$leave->scholar_id, $leave]), [
                'response' => LeaveStatus::APPROVED,
                'response_letter' => $this->responseLetter,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Leave approved successfully!');

        $this->assertTrue($leave->fresh()->isApproved());
        $this->assertEquals($this->responseLetter->hashName('scholar_leaves/response_letters'), $leave->fresh()->response_letter_path);
    }

    /** @test */
    public function user_cannot_approve_leaves_when_they_donot_have_permission_to_approve()
    {
        $this->signIn($user = create(User::class));

        $user->roles->every->revokePermissionTo('leaves:respond');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.leaves.respond', [$leave->scholar_id, $leave]))
            ->assertForbidden();

        $this->assertFalse($leave->fresh()->isApproved());
    }

    /** @test */
    public function user_can_reject_leaves_when_they_have_permission_to_respond()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('leaves:respond');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.leaves.respond', [$leave->scholar_id, $leave]), [
                'response' => LeaveStatus::REJECTED,
                'response_letter' => $this->responseLetter,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Leave rejected successfully!');

        $this->assertEquals(LeaveStatus::REJECTED, $leave->fresh()->status);
        $this->assertEquals($this->responseLetter->hashName('scholar_leaves/response_letters'), $leave->fresh()->response_letter_path);
    }

    /** @test */
    public function user_cannot_reject_leaves_when_they_do_not_have_permission_to_respond()
    {
        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('leaves:respond');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        $this->withExceptionHandling()
            ->patch(route('scholars.leaves.respond', [$leave->scholar_id, $leave]))
            ->assertForbidden();

        $this->assertNotEquals(LeaveStatus::REJECTED, $leave->fresh()->status);
    }

    /** @test */
    public function response_letter_upload_is_required_on_responding_to_a_leave()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->givePermissionTo('leaves:respond');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.leaves.respond', [$leave->scholar_id, $leave]), [
                    'response' => LeaveStatus::APPROVED,
                ]);

            $this->fail('Response letter is required was not validated');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('response_letter', $e->errors());
        }
    }

    /** @test */
    public function response_is_required_on_responding_to_a_leave()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->givePermissionTo('leaves:respond');

        $leave = create(Leave::class, 1, ['status' => LeaveStatus::APPLIED]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('scholars.leaves.respond', [$leave->scholar_id, $leave]), [
                    'response_letter' => $this->responseLetter,
                ]);

            $this->fail('Response is required was not validated');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('response', $e->errors());
        }
    }

    /** @test */
    public function user_can_view_scholar_leave_application_only_if_they_have_permission_to_respond_to_leave()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->givePermissionTo('leaves:respond');

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(factory(User::class)->states('supervisor')->create());

        $applicationPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_leaves');

        $leave = create(Leave::class, 1, [
            'scholar_id' => $scholar->id,
            'application_path' => $applicationPath,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.leaves.application', [$scholar, $leave]))
            ->assertSuccessful();
    }

    /** @test */
    public function user_can_not_view_scholar_leave_application_if_they_do_not_have_permission_to_respond_to_leaves()
    {
        Storage::fake();

        $this->signIn($user = create(User::class));

        $user->roles->every->revokePermissionTo('leaves:respond');

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(factory(User::class)->states('supervisor')->create());

        $applicationPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_leaves');

        $leave = create(Leave::class, 1, [
            'scholar_id' => $scholar->id,
            'application_path' => $applicationPath,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.leaves.application', [$scholar, $leave]))
            ->assertForbidden();
    }
}
