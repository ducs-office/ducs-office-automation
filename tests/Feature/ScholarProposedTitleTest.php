<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ScholarProposedTitleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_edit_proposed_title()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $proposedTitle = Str::random(30);

        $this->withoutExceptionHandling()
            ->patch(route('scholars.proposed_title.update', $scholar), [
                'proposed_title' => $proposedTitle,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Proposed Title for Seminar updated successfully!');
    }

    /** @test */
    public function scholar_can_not_edit_proposed_title_if_an_on_going_appeal_for_phd_seminar_exists()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $proposedTitle = Str::random(30);

        create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
            'status' => ScholarAppealStatus::APPLIED,
        ]);

        $this->withExceptionHandling()
            ->patch(route('scholars.proposed_title.update', $scholar), [
                'proposed_title' => $proposedTitle,
            ])
            ->assertUnauthorized();
    }

    /** @test */
    public function scholar_can_not_edit_proposed_title_of_another_scholar()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $proposedTitle = Str::random(30);

        $otherScholar = create(Scholar::class);

        $this->withExceptionHandling()
            ->patch(route('scholars.proposed_title.update', $otherScholar), [
                'proposed_title' => $proposedTitle,
            ])
            ->assertUnauthorized();
    }
}
