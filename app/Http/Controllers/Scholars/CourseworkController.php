<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Models\Pivot\ScholarCoursework;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseworkController extends Controller
{
    public function viewMarksheet(ScholarCoursework $course)
    {
        abort_unless($course->scholar_id == auth()->id(), 403, 'You cannot view this file!');

        return Response::download(
            Storage::path($course->marksheet_path),
            Str::after($course->marksheet_path, '/'),
            [],
            'inline'
        );
    }
}
