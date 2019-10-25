<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use \App\LetterReminder;

class AttachmentController extends Controller
{
    public function show(Request $request)
    {
        $file =$request->file;
        
        return response()->file(Storage::path($file));
    }
}
