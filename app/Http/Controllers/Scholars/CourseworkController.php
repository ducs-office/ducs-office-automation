<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Models\PhdCourse;
use App\Models\ScholarCourseworkPivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseworkController extends Controller
{
    public function viewMarksheet(PhdCourse $course)
    {
        $scholar = Auth::user();

        abort_unless($scholar->courseworks->contains($course), 403, 'You cannot view this file!');

        $pivot = ScholarCourseworkPivot::where('scholar_id', $scholar->id)
                ->where('phd_Course_id', $course->id)->get()[0];

        return Response::download(
            Storage::path($pivot->marksheet_path),
            Str::after($pivot->marksheet_path, '/'),
            [],
            'inline'
        );
    }
}
