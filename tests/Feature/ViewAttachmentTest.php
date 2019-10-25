<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\User;

class ViewAttachmentTest extends TestCase
{
   /** @test */
    use RefreshDatabase;

    public function guest_can_not_view_an_attachment()
    {
        $this->withExceptionHandling()
            ->get('/attachments')
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_view_an_attachmemt()
    {
        $this->be(create(User::class));
        $attachment = UploadedFile::fake()->create('document.pdf', 50);

        Storage::disk('local')->put("/", $attachment);
        Storage::disk('local')->assertExists($attachment->hashName());

        $this->withoutExceptionHandling()
            ->call('GET', '/attachments', ["file"=> $attachment->hashName()])
            ->assertSuccessful();
        
        Storage::delete($attachment->hashName());
        Storage::assertMissing($attachment->hashName());
    }
}
