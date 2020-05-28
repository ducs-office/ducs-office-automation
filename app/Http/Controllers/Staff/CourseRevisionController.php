<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CourseRevisionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'revised_at' => ['required', 'date', 'before_or_equal:now'],
            'attachments' => ['required', 'array', 'max:5'],
            'attachments.*' => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:200'],
        ]);

        DB::beginTransaction();

        $revision = $course->revisions()->create([
            'revised_at' => $request->revised_at,
        ]);

        $revision->attachments()->createMany(
            array_map(static function ($uploadedFile) {
                return [
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'path' => $uploadedFile->store('/course_attachments'),
                ];
            }, $request->attachments)
        );

        DB::commit();

        flash('Course Revision added!')->success();

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseRevision  $revision
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($course, CourseRevision $revision)
    {
        $this->authorize($revision);

        $revision->attachments()->delete();
        $revision->delete();

        flash('Course Revision deleted!')->success();

        return Redirect::back();
    }
}
