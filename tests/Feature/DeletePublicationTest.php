<?php

namespace Tests\Feature;

use App\Publication;
use App\Scholar;
use App\SupervisorProfile;
use App\Teacher;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function journal_publication_of_scholar_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->journals);

        $this->withoutExceptionHandling()
            ->delete(route('publications.journal.destroy', $journal))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->journals);
        $this->assertNull($journal->fresh());
    }

    /** @test */
    public function journal_publication_of_supervisor_can_be_deleted()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => Teacher::class,
            'supervisor_id' => create(Teacher::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signInTeacher($supervisor);

        $journal = create(Publication::class, 1, [
            'type' => 'journal',
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->assertCount(1, $supervisorProfile->fresh()->journals);

        $this->withoutExceptionHandling()
            ->delete(route('publications.journal.destroy', $journal))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Journal Publication deleted successfully!');

        $this->assertCount(0, $supervisorProfile->fresh()->journals);
        $this->assertNull($journal->fresh());
    }

    /** @test */
    public function conference_publication_of_scholar_can_be_deleted()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->conferences);

        $this->withoutExceptionHandling()
            ->delete(route('publications.conference.destroy', $conference))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication deleted successfully!');

        $this->assertCount(0, $scholar->fresh()->conferences);
        $this->assertNull($conference->fresh());
    }

    /** @test */
    public function conference_publication_of_supervisor_can_be_deleted()
    {
        $supervisorProfile = create(SupervisorProfile::class, 1, [
            'supervisor_type' => User::class,
            'supervisor_id' => create(User::class)->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->signIn($supervisor);
        $conference = create(Publication::class, 1, [
            'type' => 'conference',
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->assertCount(1, $supervisorProfile->fresh()->conferences);

        $this->withoutExceptionHandling()
            ->delete(route('publications.conference.destroy', $conference))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Conference Publication deleted successfully!');

        $this->assertCount(0, $supervisorProfile->fresh()->conferences);
        $this->assertNull($conference->fresh());
    }
}
