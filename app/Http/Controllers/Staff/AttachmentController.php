<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Attachment;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Attachment::class, 'attachment');
    }

    public function show(Attachment $attachment)
    {
        return Response::file(Storage::path($attachment->path));
    }

    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        return redirect()->back();
    }
}
