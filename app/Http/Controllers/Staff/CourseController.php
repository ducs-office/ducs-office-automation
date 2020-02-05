<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Programme;
use App\Course;
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
            'revisions' => function ($q) {
                return $q->orderBy('revised_at', 'desc');
            },
            'revisions.attachments'
        ])->latest()->get();

        return view('staff.courses.index', [
            'courses' => $courses,
            'course_types' => config('options.courses.types')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $types = implode(',', array_keys(config('options.courses.types')));

        $validData = $request->validate([
            'code' => ['required', 'min:3', 'max:60', 'unique:courses'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', 'in:'.$types],
            'date' => ['required', 'date', 'before_or_equal:now'],
            'attachments' => ['required', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:200', 'mimes:jpeg,jpg,png,pdf'],
        ]);

        DB::beginTransaction();

        $course = Course::create($validData);
        $revision = $course->revisions()->create([
            'revised_at' => $request->date
        ]);

        if ($request->hasFile('attachments')) {
            $revision->attachments()->createMany(
                array_map(function ($attachedFile) {
                    return [
                        'path' => $attachedFile->store('/course_attachments'),
                        'original_name' => $attachedFile->getClientOriginalName(),
                    ];
                }, $request-> attachments)
            );
        }

        DB::commit();

        flash('Course created successfully!', 'success');

        return redirect(route('staff.courses.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $types = implode(',', array_keys(config('options.courses.types')));

        $valid_data = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('courses')->ignore($course)
            ],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'type' => ['sometimes', 'required', 'in:'.$types],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,jpg,png,pdf', 'max:200'],
        ]);

        $course->update($valid_data);


        if ($request->hasFile('attachments')) {
            $revision = $course->revisions()->orderBy('revised_at', 'desc')->first();

            $revision->attachments()->createMany(
                array_map(function ($attachedFile) {
                    return [
                        'path' => $attachedFile->store('/course_attachments'),
                        'original_name' => $attachedFile->getClientOriginalName(),
                    ];
                }, $valid_data['attachments'])
            );
        }

        flash('Course updated successfully!', 'success');

        return redirect(route('staff.courses.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();

        flash('Course deleted successfully!', 'success');

        return redirect(route('staff.courses.index'));
    }
}
