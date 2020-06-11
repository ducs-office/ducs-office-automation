<?php

namespace Tests\Feature;

use App\Models\Leave;
use App\Models\Scholar;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScholarAppliesForLeavesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_applies_for_leaves()
    {
        Storage::fake();
        $fakeFile = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf');

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar = create(Scholar::class);
        $scholar->supervisors()->attach($supervisor);

        $this->signInScholar($scholar);

        $this->withoutExceptionHandling()
            ->post(route('scholars.leaves.store', $scholar), $data = [
                'from' => now()->format('Y-m-d'),
                'to' => now()->addDays(3)->format('Y-m-d'),
                'application' => $fakeFile,
                'reason' => 'Maternity Leave',
            ])->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertCount(1, $scholar->leaves);
        $this->assertEquals($data['from'], $scholar->leaves->first()->from->format('Y-m-d'));
        $this->assertEquals($data['to'], $scholar->leaves->first()->to->format('Y-m-d'));
        $this->assertEquals($data['reason'], $scholar->leaves->first()->reason);
        $this->assertEquals($data['application']->hashName('scholar_leaves'), $scholar->leaves->first()->application_path);

        Storage::assertExists($scholar->application);
    }

    /** @test */
    public function scholar_can_view_their_leave_application()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

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
    public function scholar_can_not_view_a_leave_application_that_does_not_belong_to_them()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

        $applicationPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_leaves');

        $otherScholar = create(Scholar::class);

        $leave = create(Leave::class, 1, [
            'scholar_id' => $otherScholar->id,
            'application_path' => $applicationPath,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.leaves.application', [$scholar, $leave]))
            ->assertForbidden();
    }

    /** @test */
    public function scholar_can_view_their_leave_response_letter()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

        $applicationPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_leaves');
        $reponseLetterPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_leaves/response_letters');

        $leave = create(Leave::class, 1, [
            'scholar_id' => $scholar->id,
            'application_path' => $applicationPath,
            'response_letter_path' => $reponseLetterPath,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.leaves.response_letter', [$scholar, $leave]))
            ->assertSuccessful();
    }

    /** @test */
    public function scholar_can_not_view_a_leave_response_letter_that_does_not_belong_to_them()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

        $applicationPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_leaves');
        $reponseLetterPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_leaves/response_letters');

        $otherScholar = create(Scholar::class);

        $leave = create(Leave::class, 1, [
            'scholar_id' => $otherScholar->id,
            'application_path' => $applicationPath,
            'response_letter_path' => $reponseLetterPath,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.leaves.response_letter', [$scholar, $leave]))
            ->assertForbidden();
    }
}
