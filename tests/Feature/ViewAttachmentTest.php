<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ViewAttachmentTest extends TestCase
{
    /** @test */
    use RefreshDatabase;

    public function guest_can_not_view_an_attachment()
    {
        Storage::fake();

        $letter = create(OutgoingLetter::class);
        $documentPath = UploadedFile::fake()->create('document.pdf', 50)->store('random/location');

        $attachment = $letter->attachments()->create([
            'original_name' => 'My Document',
            'path' => $documentPath,
        ]);

        $this->withExceptionHandling()
            ->get(route('staff.attachments.show', $attachment))
            ->assertRedirect();
    }

    /** @test */
    public function user_can_view_an_attachmemt()
    {
        Storage::fake();

        $letter = create(OutgoingLetter::class);
        $documentPath = UploadedFile::fake()->create('document.pdf', 50)->store('random/location');

        $attachment = $letter->attachments()->create([
            'original_name' => 'My Document',
            'path' => $documentPath,
        ]);

        $this->signIn();

        $this->withoutExceptionHandling()
            ->get(route('staff.attachments.show', $attachment))
            ->assertSuccessful();
    }
}
