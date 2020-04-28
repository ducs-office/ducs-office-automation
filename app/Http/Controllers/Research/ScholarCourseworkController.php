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
        // dd($request->completed_on, now()->format('d-m-y'));
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

    public function viewMarksheet(Scholar $scholar, PhdCourse $course)
    {
        $this->authorize('view', $scholar);

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
