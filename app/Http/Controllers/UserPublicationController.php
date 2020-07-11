<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\StorePublicationRequest;
use App\Http\Requests\Publication\UpdatePublicationRequest;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\User;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\StorePublicationTest;

class UserPublicationController extends Controller
{
    public function index(User $user)
    {
        $this->authorize('viewAny', [Publication::class, $user]);

        return view('user-publications.index', [
            'user' => $user,
        ]);
    }

    public function create(User $user)
    {
        $this->authorize('create', [Publication::class, $user]);

        return view('user-publications.create', [
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
            'types' => PublicationType::values(),
            'user' => $user,
        ]);
    }

    public function store(StorePublicationRequest $request, User $user)
    {
        $this->authorize('create', [Publication::class, $user]);

        $validData = $request->validated();

        $publication = $user->publications()->create(
            $validData
        );

        $publication->coAuthors()->createMany($request->coAuthorsDetails());

        flash('Publication added successfully')->success();

        return redirect(route('users.publications.index', $user));
    }

    public function edit(User $user, Publication $publication)
    {
        $this->authorize('update', $publication);

        return view('user-publications.edit', [
            'publication' => $publication,
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
            'user' => $user,
        ]);
    }

    public function update(UpdatePublicationRequest $request, User $user, Publication $publication)
    {
        $this->authorize('update', $publication);

        $validData = $request->validated();

        $publication->update($validData);

        $publication->coAuthors()->createMany($request->coAuthorsDetails());

        flash('Publication updated successfully!')->success();

        return redirect(route('users.publications.index', $user));
    }

    public function destroy(User $user, Publication $publication)
    {
        $this->authorize('delete', $publication);

        $publication->delete();

        flash('Publication deleted successfully!')->success();

        return back();
    }

    public function show(User $user, Publication $publication)
    {
        $this->authorize('view', $publication);

        abort_unless(Storage::exists($publication->document_path), 404, 'File Does not exist!');

        return Response::file(Storage::path($publication->document_path));
    }
}
