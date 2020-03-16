<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Scholar;
use Illuminate\Http\Request;

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

    public function complete(Scholar $scholar, $courseId)
    {
        $this->authorize('scholars.coursework.complete', $scholar);

        $scholar->courseworks()
            ->updateExistingPivot($courseId, ['completed_at' => now()]);

        flash('Coursework marked completed!')->success();

        return redirect()->back();
    }
}
