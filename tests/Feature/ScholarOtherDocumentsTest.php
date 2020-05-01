<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ScholarOtherDocumentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_other_documents_if_they_have_permission_to_add()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('scholar other documents:add');

        Storage::fake();

        $document = UploadedFile::fake()->create('document.pdf', 50, 'application/pdf');
        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->otherDocuments());

        $this->withoutExceptionHandling()
            ->post(route('research.scholars.documents.store', $scholar), [
                'document' => $document,
                'description' => $description = 'Document description',
                'date' => $date = '2019-09-12',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Document added successfully!');

        $this->assertCount(1, $scholar->fresh()->otherDocuments());
        $this->assertEquals($document->hashName('scholar_documents'), $scholar->fresh()->otherDocuments()->first()->path);
        $this->assertEquals($description, $scholar->fresh()->otherDocuments()->first()->description);
        $this->assertEquals($date, $scholar->fresh()->otherDocuments()->first()->date->format('Y-m-d'));

        Storage::assertExists($scholar->fresh()->otherDocuments()->first()->path);
    }

    /** @test */
    public function user_can_not_upload_other_documents_if_they_do_not_have_permission_to_add()
    {
        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('scholar other documents:add');

        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->otherDocuments());

        Storage::fake();

        $document = UploadedFile::fake()->create('document.pdf', 50, 'application/pdf');

        $this->withExceptionHandling()
            ->post(route('research.scholars.documents.store', $scholar), [
                'document' => $document,
                'description' => 'Document Description',
                'date' => $date = '2019-09-12',
            ])
            ->assertForbidden();

        $this->assertCount(0, $scholar->fresh()->otherDocuments());
    }

    /** @test */
    public function other_document_can_be_viewed_if_they_are_authorized()
    {
        $scholar = create(Scholar::class);
        $otherDocument = create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarDocumentType::OTHER_DOCUMENT,
        ]);
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholars:view');

        $this->signIn($user = create(User::class), $role->name);

        $this->withoutExceptionHandling()
            ->get(route('research.scholars.documents.attachment', [
                'scholar' => $scholar,
                'document' => $otherDocument,
            ]))
            ->assertSuccessful();
    }

    /** @test */
    public function other_documents_can_not_be_viewed_if_they_are_not_authorized()
    {
        $role = Role::create(['name' => 'randomRole']);
        $role->revokePermissionTo('scholars:view');

        $this->signIn($user = create(User::class), $role->name);

        $user->revokePermissionTo('scholars:view');

        $scholar = create(Scholar::class);
        $otherDocument = create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarDocumentType::OTHER_DOCUMENT,
        ]);

        $this->withExceptionHandling()
            ->get(route('research.scholars.documents.attachment', [
                'scholar' => $scholar,
                'document' => $otherDocument,
            ]))
            ->assertForbidden();
    }
}
