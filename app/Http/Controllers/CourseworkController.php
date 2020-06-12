<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkCourseworkCompletedRequest;
use App\Http\Requests\StoreCourseWorkRequest;
use App\Models\PhdCourse;
use App\Models\Pivot\ScholarCoursework;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseworkController extends Controller
{
    public function index(Scholar $scholar)
    {
        return view('pre-phd-courseworks', [
            'courses' => PhdCourse::whereNotIn('id', $scholar->courseworks()->allRelatedIds())->get(),
            'scholar' => $scholar->load('courseworks'),
        ]);
    }

    public function store(Request $request, Scholar $scholar)
    {
        $this->authorize('create', [ScholarCoursework::class, $scholar]);

        $request->validate([
            'course_id' => ['required', 'numeric', 'exists:phd_courses,id'],
        ]);

        $scholar->courseworks()->syncWithoutDetaching($request->course_id);

        flash('Coursework added to scholar profile!')->success();

        return redirect()->back();
    }

    public function complete(Scholar $scholar, $courseId, MarkCourseworkCompletedRequest $request)
    {
        $this->authorize('markCompleted', ScholarCoursework::class);

        $request->validated();

        $scholar->courseworks()
            ->updateExistingPivot($courseId, [
                'completed_on' => $request->completed_on,
                'marksheet_path' => $request->file('marksheet')->store('scholar_marksheets'),
            ]);

        flash('Coursework marked completed!')->success();

        return redirect()->back();
    }

    public function show(Scholar $scholar, ScholarCoursework $course)
    {
        $this->authorize('view', [$course, $scholar]);

        abort_unless($course->marksheet_path != null, 404, 'File Not Found!');

        return Response::download(
            Storage::path($course->marksheet_path),
            Str::after($course->marksheet_path, '/'),
            [],
            'inline'
        );
    }
}
