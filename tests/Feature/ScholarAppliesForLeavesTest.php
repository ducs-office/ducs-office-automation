<?php

namespace Tests\Feature;

use App\Scholar;
use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScholarAppliesForLeavesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisor_adds_applied_leave_for_scholar()
    {
        Storage::fake();
        $fakeFile = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf');

        $teacher = create(Teacher::class);
        $supervisorProfile = $teacher->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, ['supervisor_profile_id' => $supervisorProfile->id]);

        $this->signInScholar($scholar);

        $this->withoutExceptionHandling()
            ->post(route('scholars.leaves.store', $scholar), $data = [
                'from' => now()->format('Y-m-d'),
                'to' => now()->addDays(3)->format('Y-m-d'),
                'document' => $fakeFile,
                'reason' => 'Maternity Leave',
            ])->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertCount(1, $scholar->leaves);
        $this->assertEquals($data['from'], $scholar->leaves->first()->from->format('Y-m-d'));
        $this->assertEquals($data['to'], $scholar->leaves->first()->to->format('Y-m-d'));
        $this->assertEquals($data['reason'], $scholar->leaves->first()->reason);
        $this->assertEquals($data['document']->hashName('scholar_leaves'), $scholar->leaves->first()->document_path);

        Storage::assertExists($scholar->document_path);
    }
}
