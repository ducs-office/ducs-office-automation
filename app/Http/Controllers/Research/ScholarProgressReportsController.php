<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\ScholarDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response as Response;
use Illuminate\Support\Facades\Storage;

class ScholarProgressReportsController extends Controller
{
    public function store(Request $request, Scholar $scholar)
    {
        $this->authorize('scholars.progress_reports.store', $scholar);

        $request->validate([
            'progress_report' => ['required', 'file', 'mimetypes:application/pdf, image/*', 'max:200'],
            'description' => ['required', 'string', 'min:5', 'max:250'],
        ]);

        $scholar->documents()->create([
            'type' => ScholarDocumentType::PROGRESS_REPORT,
            'path' => $request->file('progress_report')->store('scholar_documents'),
            'description' => $request->input('description'),
        ]);

        flash('Progress Report added successfully!')->success();

        return back();
    }

    public function viewAttachment(Scholar $scholar, ScholarDocument $document)
    {
        $this->authorize('view', $scholar);

        return Response::file(Storage::path($document->path));
    }
}
