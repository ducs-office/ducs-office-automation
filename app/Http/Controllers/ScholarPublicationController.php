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

class ScholarPublicationController extends Controller
{
    public function index(Scholar $scholar)
    {
        $this->authorize('viewAny', [Publication::class, $scholar]);

        return view('scholar-publications.index', [
            'scholar' => $scholar,
        ]);
    }

    public function create(Scholar $scholar)
    {
        $this->authorize('create', [Publication::class, $scholar]);

        return view('scholar-publications.create', [
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
            'types' => PublicationType::values(),
            'scholar' => $scholar,
        ]);
    }

    public function store(StorePublicationRequest $request, Scholar $scholar)
    {
        $this->authorize('create', [Publication::class, $scholar]);

        $validData = $request->validated();

        $publication = $scholar->publications()->create(
            $validData +
            ['document_path' => $request->storeDocument()],
        );

        $publication->coAuthors()->createMany($request->coAuthorsDetails());

        flash('Publication added successfully')->success();

        return redirect(route('scholars.publications.index', $scholar));
    }

    public function edit(Scholar $scholar, Publication $publication)
    {
        $this->authorize('update', $publication);

        return view('scholar-publications.edit', [
            'publication' => $publication,
            'citationIndexes' => CitationIndex::values(),
            'months' => array_map(function ($m) {
                return Carbon::createFromFormat('m', $m)->format('F');
            }, range(1, 12)),
            'currentYear' => now()->format('Y'),
            'scholar' => $scholar,
        ]);
    }

    public function update(UpdatePublicationRequest $request, Scholar $scholar, Publication $publication)
    {
        $this->authorize('update', $publication);

        $validData = $request->validated();

        $publication->update(array_merge(
            $validData,
            $request->updateDocumentConditionally()
        ));

        $publication->coAuthors()->createMany($request->coAuthorsDetails());

        flash('Publication updated successfully!')->success();

        return redirect(route('scholars.publications.index', $scholar));
    }

    public function destroy(Scholar $scholar, Publication $publication)
    {
        $this->authorize('delete', $publication);

        $publication->delete();

        flash('Publication deleted successfully!')->success();

        return back();
    }

    public function show(Scholar $scholar, Publication $publication)
    {
        $this->authorize('view', $publication);

        return Response::file(Storage::path($publication->document_path));
    }
}
