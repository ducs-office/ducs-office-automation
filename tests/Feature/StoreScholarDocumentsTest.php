<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\User;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreScholarDocumentsTest extends TestCase
{
    use RefreshDatabase;

    protected function getDocumentFormDetails()
    {
        Storage::fake();

        $fakeFile = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf');

        return [
            'document' => $fakeFile,
            'type' => ScholarDocumentType::JOINING_LETTER,
            'description' => 'some description',
            'date' => '2011-10-10',
        ];
    }

    /** @test */
    public function scholar_can_add_their_documents()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $document = $this->getDocumentFormDetails();

        $this->withoutExceptionHandling()
            ->post(route('scholars.documents.store', $scholar), $document)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Document added successfully!');

        $freshScholar = $scholar->fresh();

        $this->assertCount(1, $freshScholar->documents);

        $this->assertEquals($document['type'], $freshScholar->documents->first()->type);
        $this->assertEquals($document['description'], $freshScholar->documents->first()->description);
        $this->assertEquals($document['date'], $freshScholar->documents->first()->date->format('Y-m-d'));
        $this->assertEquals($document['document']->hashName('scholar_documents'), $freshScholar->documents->first()->path);
    }

    /** @test */
    public function scholar_can_not_add_other_scholars_documents()
    {
        $this->signInScholar();

        $document = $this->getDocumentFormDetails();

        $otherScholar = create(Scholar::class);

        $this->withExceptionHandling()
            ->post(route('scholars.documents.store', $otherScholar), $document)
            ->assertForbidden();
    }

    /** @test */
    public function user_can_add_scholar_documents_if_they_have_permission_to_add()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar documents:add');

        $scholar = create(Scholar::class);
        $document = $this->getDocumentFormDetails();

        $this->withoutExceptionHandling()
            ->post(route('scholars.documents.store', $scholar), $document)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Document added successfully!');

        $freshScholar = $scholar->fresh();

        $this->assertCount(1, $freshScholar->documents);

        $this->assertEquals($document['type'], $freshScholar->documents->first()->type);
        $this->assertEquals($document['description'], $freshScholar->documents->first()->description);
        $this->assertEquals($document['date'], $freshScholar->documents->first()->date->format('Y-m-d'));
        $this->assertEquals($document['document']->hashName('scholar_documents'), $freshScholar->documents->first()->path);
    }

    /** @test */
    public function user_can_not_add_scholar_documents_if_they_have_permission_to_add()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar documents:add');

        $scholar = create(Scholar::class);
        $document = $this->getDocumentFormDetails();

        $user->roles->first()->revokePermissionTo('scholar documents:add');

        $this->withExceptionHandling()
            ->post(route('scholars.documents.store', $scholar), $document)
            ->assertForbidden();
    }
}
