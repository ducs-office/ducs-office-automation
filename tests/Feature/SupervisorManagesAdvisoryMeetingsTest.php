<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupervisorManagesAdvisoryMeetingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_can_add_advisory_meetings_of_their_scholars()
    {
        Storage::fake();

        $this->signIn($user = factory(User::class)->states('supervisor')->create());

        $scholar = create(Scholar::class);

        $scholar->supervisors()->attach($user);

        $this->withoutExceptionHandling()
            ->post(route('scholars.advisory_meetings.store', $scholar), [
                'date' => now()->subDays(2)->format('Y-m-d'),
                'minutes_of_meeting' => UploadedFile::fake()
                    ->create('minutes_of_meeting.pdf', 15),
            ])->assertRedirect();

        $this->assertCount(1, $scholar->fresh()->advisoryMeetings);
    }

    /** @test */
    public function supervisor_can_not_add_advisory_meetings_of_other_scholars()
    {
        Storage::fake();

        $this->signIn($user = factory(User::class)->states('supervisor')->create());

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(factory(User::class)->states('supervisor')->create());

        $this->withExceptionHandling()
            ->post(route('scholars.advisory_meetings.store', $scholar), [
                'date' => now()->subDays(2)->format('Y-m-d'),
                'minutes_of_meeting' => UploadedFile::fake()
                    ->create('minutes_of_meeting.pdf', 15),
            ])->assertForbidden();

        $this->assertCount(0, $scholar->fresh()->advisoryMeetings);
    }

    /** @test */
    public function scholar_can_not_add_their_advisory_meetings()
    {
        Storage::fake();

        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach(create(User::class));

        $this->signInScholar($scholar);

        $this->withExceptionHandling()
            ->post(route('scholars.advisory_meetings.store', $scholar), [
                'date' => now()->subDays(2)->format('Y-m-d'),
                'minutes_of_meeting' => UploadedFile::fake()
                    ->create('minutes_of_meeting.pdf', 15),
            ])->assertRedirect(route('login-form'));

        $this->assertCount(0, $scholar->fresh()->advisoryMeetings);
    }

    /** @test */
    public function supervisor_can_view_minutes_of_meeting_for_a_advisory_meeting_of_their_scholar()
    {
        Storage::fake();
        $file = UploadedFile::fake()->create('minutes_of_meeting.pdf', 15, 'document/*');

        $scholar = create(Scholar::class);

        $scholar->supervisors()->attach($user = factory(User::class)->states('supervisor')->create());

        $meeting = $scholar->advisoryMeetings()->create([
            'date' => now()->subDays(2),
            'minutes_of_meeting_path' => $file->store('advisory_meetings'),
        ]);

        $this->signIn($scholar->currentSupervisor);

        $this->withoutExceptionHandling()
            ->get(route('scholars.advisory_meetings.show', [$scholar, $meeting]))
            ->assertSuccessful();
    }

    /** @test */
    public function supervisor_can_not_view_minutes_of_meeting_for_a_advisory_meeting_of_others_scholar()
    {
        Storage::fake();
        $file = UploadedFile::fake()->create('minutes_of_meeting.pdf', 15, 'document/*');

        $scholar = create(Scholar::class);

        $scholar->supervisors()->attach(factory(User::class)->states('supervisor')->create());

        $meeting = $scholar->advisoryMeetings()->create([
            'date' => now()->subDays(2),
            'minutes_of_meeting_path' => $file->store('advisory_meetings'),
        ]);

        $this->signIn(factory(User::class)->states('supervisor')->create());

        $this->withExceptionHandling()
            ->get(route('scholars.advisory_meetings.show', [$scholar, $meeting]))
            ->assertForbidden();
    }
}
