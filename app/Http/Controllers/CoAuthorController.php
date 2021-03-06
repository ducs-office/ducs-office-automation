<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\StoreCoAuthorRequest;
use App\Models\CoAuthor;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CoAuthorController extends Controller
{
    public function show(Publication $publication, CoAuthor $coAuthor)
    {
        $this->authorize('view', $coAuthor, $publication);

        return Response::file(Storage::path($coAuthor->noc_path));
    }

    public function store(StoreCoAuthorRequest $request, Publication $publication)
    {
        $this->authorize('create', [CoAuthor::class, $publication]);

        $validData = $request->validated();

        $publication->coAuthors()->create([
            'name' => $validData['name'],
            'noc_path' => (array_key_exists('noc', $validData) && $validData['noc'])
                ? $validData['noc']->store('/publications/co_authors_noc')
                : '',
        ]);

        flash('Co-Author added successfully!')->success();

        return redirect()->back();
    }

    public function destroy(Publication $publication, CoAuthor $coAuthor)
    {
        $this->authorize('delete', [$coAuthor, $publication]);

        $coAuthor->delete();

        return back();
    }
}
