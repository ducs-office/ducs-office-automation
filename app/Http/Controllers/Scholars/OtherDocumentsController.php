<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Models\ScholarDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class OtherDocumentsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ScholarDocument $document)
    {
        abort_unless(
            $document->scholar->id === auth()->id(),
            '403',
            'You can not view this file'
        );

        return Response::file(Storage::path($document->path));
    }
}
