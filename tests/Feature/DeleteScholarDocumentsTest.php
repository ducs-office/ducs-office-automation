<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteScholarDocumentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_delete_their_documents()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $document = create(ScholarDocument::class, 1, ['scholar_id' => $scholar->id]);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.documents.destroy', [
                'scholar' => $scholar,
                'document' => $document,
            ]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Document deleted successfully!');

        $this->assertCount(0, $scholar->documents);
    }

    /** @test */
    public function scholar_can_not_other_scholars_documents()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $otherScholar = create(Scholar::class);

        $document = create(ScholarDocument::class, 1, ['scholar_id' => $otherScholar->id]);

        $this->withExceptionHandling()
            ->delete(route('scholars.documents.destroy', [
                'scholar' => $otherScholar,
                'document' => $document,
            ]))->assertForbidden();

        $this->assertCount(1, $otherScholar->documents);
    }

    /** @test */
    public function user_can_delete_scholar_documents_only_if_they_have_permission_to_delete()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar documents:delete');

        $scholar = create(Scholar::class);
        $document = create(ScholarDocument::class, 1, ['scholar_id' => $scholar->id]);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.documents.destroy', [
                'scholar' => $scholar,
                'document' => $document,
            ]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Document deleted successfully!');

        Storage::assertMissing($document->path);
        $this->assertCount(0, $scholar->documents);
    }

    /** @test */
    public function user_can_not_delete_scholar_documents_if_they_do_not_have_permission_to_delete()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar documents:delete');

        $scholar = create(Scholar::class);
        $document = create(ScholarDocument::class, 1, ['scholar_id' => $scholar->id]);

        $user->roles->first()->revokePermissionTo('scholar documents:delete');

        $this->withExceptionHandling()
            ->delete(route('scholars.documents.destroy', [
                'scholar' => $scholar,
                'document' => $document,
            ]))->assertForbidden();

        $this->assertCount(1, $scholar->documents);
    }
}
