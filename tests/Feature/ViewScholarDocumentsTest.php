<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewScholarDocumentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_documents_if_they_have_permission_to_view()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar documents:view');

        $scholar = create(Scholar::class);

        $document = create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
             ->get(route('scholars.documents.show', [
                 'scholar' => $scholar,
                 'document' => $document,
             ]))
             ->assertSuccessful();
    }

    /** @test */
    public function user_can_not_view_documents_if_they_do_not_have_permission_to_view()
    {
        $this->signIn($user = create(User::class), 'randomRole');

        $user->roles->first()->givePermissionTo('scholar documents:view');

        $scholar = create(Scholar::class);

        $document = create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $user->roles->first()->revokePermissionTo('scholar documents:view');

        $this->withExceptionHandling()
             ->get(route('scholars.documents.show', [$scholar, $document]))
             ->assertForbidden();
    }

    /** @test */
    public function scholar_can_view_their_documents()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $document = create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.documents.show', [$scholar, $document]))
            ->assertSuccessful();
    }

    /** @test */
    public function scholar_can_not_view_other_scholars_documents()
    {
        $this->signInScholar();

        $scholar = create(Scholar::class);

        $document = create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.documents.show', [$scholar, $document]))
            ->assertForbidden();
    }
}
