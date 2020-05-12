<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\CoAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CoAuthorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CoAuthor::class, 'coAuthor');
    }

    public function show(CoAuthor $coAuthor)
    {
        return Response::file(Storage::path($coAuthor->noc_path));
    }

    public function destroy(CoAuthor $coAuthor)
    {
        $coAuthor->delete();
        return back();
    }
}
