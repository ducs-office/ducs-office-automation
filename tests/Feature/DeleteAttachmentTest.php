<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteAttachmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_attachments()
    {
        Storage::fake();

        $letter = create(OutgoingLetter::class);
        $attachment = $letter->attachments()->create([
            'original_name' => 'Some random file.jpg',
            'path' => UploadedFile::fake()->image('random_image.jpg')->store('random/location')
        ]);

        $this->signIn();

        $this->withoutExceptionHandling()
            ->from('/outgoing_letters')
            ->delete('/attachments/' . $attachment->id)
            ->assertRedirect('/outgoing_letters');

        Storage::assertMissing($attachment->path);
        $this->assertNull($attachment->fresh());
    }
}
