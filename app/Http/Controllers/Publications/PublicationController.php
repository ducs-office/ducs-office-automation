<?php

namespace App\Http\Controllers\Publications;

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

class PublicationController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Publication::class, 'publication');
    }

    public function create()
    {
        return view('publications.create', [
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
            'types' => PublicationType::values(),
        ]);
    }

    public function store(StorePublicationRequest $request)
    {
        $user = $request->user();
        $validData = $request->validated();

        $publication = $user->publications()->create(
            $validData +
            ['document_path' => $request->storeDocument()],
        );

        $publication->coAuthors()->createMany($request->coAuthorsDetails());

        flash('Publication added successfully')->success();

        return redirect()->back();
    }

    public function edit(Publication $publication)
    {
        return view('publications.edit', [
            'publication' => $publication,
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
        ]);
    }

    public function update(UpdatePublicationRequest $request, Publication $publication)
    {
        $validData = $request->validated();

        $publication->update(array_merge(
            $validData,
            $request->updateDocumentConditionally()
        ));

        $publication->coAuthors()->createMany($request->coAuthorsDetails());

        flash('Publication updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(Publication $publication)
    {
        $publication->delete();

        flash('Publication deleted successfully!')->success();

        return back();
    }

    public function show(Publication $publication)
    {
        return Response::file(Storage::path($publication->document_path));
    }
}
