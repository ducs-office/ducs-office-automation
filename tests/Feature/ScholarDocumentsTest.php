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

class ScholarDocumentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_documents_if_they_have_permission_to_add()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('scholar documents:add');

        Storage::fake();

        $document = UploadedFile::fake()->create('document.pdf', 50, 'application/pdf');
        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->documents);

        $this->withoutExceptionHandling()
            ->post(route('research.scholars.documents.store', $scholar), [
                'document' => $document,
                'description' => $description = 'Document description',
                'type' => $type = ScholarDocumentType::JOINING_LETTER,
                'date' => $date = '2019-09-12',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Document added successfully!');

        $freshScholar = $scholar->fresh();

        $this->assertCount(1, $freshScholar->documents);
        $this->assertEquals($document->hashName('scholar_documents'), $freshScholar->documents->first()->path);
        $this->assertEquals($description, $freshScholar->documents->first()->description);
        $this->assertEquals($type, $freshScholar->documents->first()->type);
        $this->assertEquals($date, $freshScholar->documents->first()->date->format('Y-m-d'));

        Storage::assertExists($freshScholar->documents->first()->path);
    }

    /** @test */
    public function user_can_not_upload_documents_if_they_do_not_have_permission_to_add()
    {
        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('scholar documents:add');

        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->documents);

        Storage::fake();

        $document = UploadedFile::fake()->create('document.pdf', 50, 'application/pdf');

        $this->withExceptionHandling()
            ->post(route('research.scholars.documents.store', $scholar), [
                'document' => $document,
                'description' => 'Document Description',
                'type' => ScholarDocumentType::JOINING_LETTER,
                'date' => '2019-09-12',
            ])
            ->assertForbidden();

        $this->assertCount(0, $scholar->fresh()->documents);
    }
}
