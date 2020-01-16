<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CourseRevisionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'revised_at' => ['required', 'date', 'before_or_equal:now'],
            'attachments' => ['required', 'array', 'max:5'],
            'attachments.*' => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:200']
        ]);

        DB::beginTransaction();

        $revision = $course->revisions()->create([
            'revised_at' => $request->revised_at
        ]);

        $revision->attachments()->createMany(
            array_map(function ($uploadedFile) {
                return [
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'path' => $uploadedFile->store('/course_attachments')
                ];
            }, $request->attachments)
        );

        DB::commit();

        flash('Course Revision added!')->success();

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CourseRevision  $courseRevision
     * @return \Illuminate\Http\Response
     */
    public function show(CourseRevision $courseRevision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CourseRevision  $courseRevision
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseRevision $courseRevision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseRevision  $courseRevision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseRevision $courseRevision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseRevision  $courseRevision
     * @return \Illuminate\Http\Response
     */
    public function destroy($course, CourseRevision $courseRevision)
    {
        $courseRevision->attachments()->delete();
        $courseRevision->delete();

        flash('Course Revision deleted!')->success();

        return Redirect::back();
    }
}
