<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\ScholarCourseworkPivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ScholarCourseworkController extends Controller
{
    public function store(Request $request, Scholar $scholar)
    {
        $this->authorize('scholars.coursework.store', $scholar);

        $request->validate([
            'course_ids' => ['required', 'array', 'max:3'],
            'course_ids.*' => ['required', 'numeric', 'exists:phd_courses,id'],
        ]);

        $scholar->courseworks()->syncWithoutDetaching($request->course_ids);

        flash('Coursework added to scholar profile!')->success();

        return redirect()->back();
    }

    public function complete(Scholar $scholar, $courseId, Request $request)
    {
        $this->authorize('phd course work:mark completed');

        $request->validate([
            'marksheet' => ['required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'],
            'completed_on' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $scholar->courseworks()
            ->updateExistingPivot($courseId, [
                'completed_on' => $request->completed_on,
                'marksheet_path' => $request->file('marksheet')->store('scholar_marksheets'),
            ]);

        flash('Coursework marked completed!')->success();

        return redirect()->back();
    }

    public function viewMarksheet(Scholar $scholar, ScholarCourseworkPivot $course)
    {
        $this->authorize('view', $scholar);

        abort_unless($course->marksheet_path != null, 404, 'File Not Found!');

        return Response::download(
            Storage::path($course->marksheet_path),
            Str::after($course->marksheet_path, '/'),
            [],
            'inline'
        );
    }
}
