<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ScholarDocumentsController extends Controller
{
    public function view(Scholar $scholar, ScholarDocument $document)
    {
        $this->authorize('view', $document);

        return Response::file(Storage::path($document->path));
    }
}
