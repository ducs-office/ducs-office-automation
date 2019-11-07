<?php

namespace Tests\Feature;

use App\LetterReminder;
use App\OutgoingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StoreLetterRemindersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_store_letter_reminders()
    {
        $letter = create(OutgoingLetter::class);

        $this->withExceptionHandling()
            ->post("/outgoing_letters/{$letter->id}/reminders")
            ->assertRedirect('login');

        $this->assertEquals(0, LetterReminder::count());
    }

    /** @test */
    public function user_can_store_letter_reminders()
    {
        Storage::fake();

        $role = Role::create(['name' => 'random']);
        $permission = Permission::firstOrCreate(['name' => 'create letter reminders']);

        $role->givePermissionTo($permission);

        $this->signIn(create(User::class), $role->name);

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => auth()->id()
        ]);

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            'letter_id' => $letter->id,
            'attachments' => [
                $photo = UploadedFile::fake()->image('Scanned.jpg'),
                $document = UploadedFile::fake()->create('Document.pdf')
            ],
        ];

        $this->withOutExceptionHandling()
            ->post("/outgoing_letters/{$letter->id}/reminders", $reminder);

        $this->assertEquals(1, LetterReminder::count());
        $reminder = LetterReminder::first();
        $this->assertCount(2, $reminder->attachments);
        $this->assertEquals(
            'letter_attachments/outgoing/reminders/' . $photo->hashName(),
            $reminder->attachments->first()->path
        );
        $this->assertEquals(
            'letter_attachments/outgoing/reminders/' . $document->hashName(),
            $reminder->attachments->last()->path
        );

        Storage::assertExists($reminder->attachments->first()->path);
        Storage::assertExists($reminder->attachments->last()->path);
    }


    /** @test */
    public function request_validates_atleast_on_attachment_is_uploaded()
    {
        $this->be(create(User::class));

        $letter = create(OutgoingLetter::class);

        $reminder = [
            'attachments' => [
            ],
        ];

        try {
            $this->withExceptionHandling()
                ->post("/outgoing_letters/{$letter->id}/reminders", $reminder);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('attachments', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }
}
