<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteAttachmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_attachments()
    {
        Storage::fake();

        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class, 1, ['creator_id' => Auth::id()]);

        $attachments = $letter->attachments()->createMany([
            [
                'original_name' => 'Some random file.jpg',
                'path' => UploadedFile::fake()->image('random_image.jpg')->store('random/location'),
            ],
            [
                'original_name' => 'Some other random file.jpg',
                'path' => UploadedFile::fake()->image('random_image.jpg')->store('random/location'),
            ],
        ]);

        $this->withoutExceptionHandling()
            ->delete(route('staff.attachments.destroy', $attachments[0]))
            ->assertRedirect();

        Storage::assertMissing($attachments[0]->path);
        $this->assertNull($attachments[0]->fresh());
    }

    /** @test */
    public function admin_cannot_delete_attachments_if_related_letter_has_only_one()
    {
        Storage::fake();

        $letter = create(OutgoingLetter::class);
        $attachment = $letter->attachments()->create([
            'original_name' => 'Some random file.jpg',
            'path' => UploadedFile::fake()->image('random_image.jpg')->store('random/location'),
        ]);

        $this->signIn();

        $this->withExceptionHandling()

            ->delete(route('staff.attachments.destroy', $attachment))
            ->assertForbidden();

        Storage::assertExists($attachment->path);
        $this->assertNotNull($attachment->fresh());
    }
}
