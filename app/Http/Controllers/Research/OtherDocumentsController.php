<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\ScholarDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class OtherDocumentsController extends Controller
{
    public function store(Request $request, Scholar $scholar)
    {
        $this->authorize('scholars.other_documents.store', $scholar);

        $request->validate([
            'document' => ['required', 'file', 'mimetypes:application/pdf, image/*', 'max:200'],
            'description' => ['required', 'string', 'min:5', 'max:250'],
        ]);

        $scholar->documents()->create([
            'type' => ScholarDocumentType::OTHER_DOCUMENT,
            'path' => $request->file('document')->store('scholar_documents'),
            'description' => $request->input('description'),
        ]);

        flash('Document added successfully!')->success();

        return back();
    }

    public function viewAttachment(Scholar $scholar, ScholarDocument $document)
    {
        $this->authorize('view', $scholar);

        return Response::file(Storage::path($document->path));
    }
}
