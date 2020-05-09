<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Types\ScholarDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ScholarDocumentsController extends Controller
{
    public function store(Request $request, Scholar $scholar)
    {
        $this->authorize('scholars.documents.store', $scholar);

        $request->validate([
            'document' => ['required', 'file', 'mimetypes:application/pdf, image/*', 'max:200'],
            'description' => ['required', 'string', 'min:5', 'max:250'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'type' => [
                'required', 'string',
                Rule::in(ScholarDocumentType::values()),
            ],
        ]);

        $scholar->documents()->create([
            'type' => $request->type,
            'path' => $request->file('document')->store('scholar_documents'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
        ]);

        flash('Document added successfully!')->success();

        return back();
    }
}
