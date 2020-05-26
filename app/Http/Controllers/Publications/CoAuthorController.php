<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
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

    public function store(Request $request, Publication $publication)
    {
        $this->authorize('create', [CoAuthor::class, $publication]);

        $validData = $request->validate([
            'is_supervisor' => ['required_without_all:is_cosupervisor,name'],
            'is_cosupervisor' => ['required_without_all:is_supervisor,name'],
            'name' => ['required_without_all:is_supervisor,is_cosupervisor', 'string'],
            'noc' => ['nullable', 'file', 'max:200', 'mimeTypes:application/pdf, image/*'],
        ]);

        // dd($validData);

        if ($validData['is_supervisor']) {
            $publication->coAuthors()->create([
                'type' => 1,
                'user_id' => $publication->author->currentSupervisor->id,
            ]);
        } elseif ($validData['is_cosupervisor']) {
            $publication->coAuthors()->create([
                'type' => 2,
                'user_id' => $publication->author->currentCosupervior->id,
            ]);
        } else {
            $publication->coAuthors()->create([
                'type' => 0,
                'name' => $validData['name'],
                'noc_path' => ($validData['noc']) ? $validData['noc']->store('/publications/co_authors_noc') : '',
            ]);
        }

        flash('Co-Author added successfully!')->success();

        return redirect()->back();
    }

    public function update(Request $request, Publication $publication, CoAuthor $coAuthor)
    {
        $this->authorize('update', [$coAuthor, $publication]);

        $validData = $request->validate([
            'name' => ['sometimes', 'required'],
            'noc' => ['sometimes', 'required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'],
        ]);

        if ($request->noc) {
            $validData = array_merge($validData, [
                'noc_path' => $request->file('noc')->store('publications/co_authors_noc'),
            ]);
        }

        $coAuthor->update($validData);

        flash('Co-Author updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(Publication $publication, CoAuthor $coAuthor)
    {
        $this->authorize('delete', [$coAuthor, $publication]);

        $coAuthor->delete();
        return back();
    }
}
