<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreCourseRequest;
use App\Http\Requests\Staff\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Course::class, 'course');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with([
            'revisions' => static function ($query) {
                return $query->orderBy('revised_at', 'desc');
            },
            'revisions.attachments',
        ])->latest()->get();

        return view('staff.courses.index', [
            'courses' => $courses,
            'courseTypes' => config('options.courses.types'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Staff\StoreCourseRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseRequest $request)
    {
        DB::beginTransaction();

        Course::create($request->validated())
            ->revisions()
            ->create([
                'revised_at' => $request->date,
            ])
            ->attachments()
            ->createMany($request->storeAttachments());

        DB::commit();

        flash('Course created successfully!', 'success');

        return redirect(route('staff.courses.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Staff\UpdateCourseRequest  $request
     * @param  \App\Models\Course  $course
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        DB::beginTransaction();

        $course->update($request->validated());

        if ($attachments = $request->storeAttachments()) {
            $revision = $course->revisions()->orderBy('revised_at', 'desc')->first();
            $revision->attachments()->createMany($attachments);
        }

        DB::commit();

        flash('Course updated successfully!', 'success');

        return redirect(route('staff.courses.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();

        flash('Course deleted successfully!', 'success');

        return redirect(route('staff.courses.index'));
    }
}
