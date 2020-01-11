<?php

namespace App\Http\Controllers;

use App\Programme;
use App\Course;
use Illuminate\Http\Request;
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
        $courses = Course::latest()->with(['programmes'])->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'min:3', 'max:60', 'unique:courses'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', 'in:Core,Open Elective,General Elective'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:200', 'mimes:jpeg,jpg,png,pdf'],
        ]);
        
        $course = Course::create($request->only(['code', 'name', 'type']));

        if ($request->hasFile('attachments')) {
            $course->attachments()->createMany(
                array_map(function ($attachedFile) {
                    return [
                        'path' => $attachedFile->store('/course_attachments'),
                        'original_name' => $attachedFile->getClientOriginalName(),
                    ];
                }, $request-> attachments)
            );
        }

        flash('Course created successfully!', 'success');

        return redirect('/courses');
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
        $data = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('courses')->ignore($course)
            ],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'type' => ['sometimes', 'required', 'in:Core,Open Elective,General Elective'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,jpg,png,pdf', 'max:200'],
        ]);

        $course->update($request->only(['code', 'name', 'type']));
        if ($request->hasFile('attachments')) {
            $course->attachments()->createMany(
                array_map(function ($attachedFile) {
                    return [
                        'path' => $attachedFile->store('/course_attachments'),
                        'original_name' => $attachedFile->getClientOriginalName(),
                    ];
                }, $data['attachments'])
            );
        }

        flash('Course updated successfully!', 'success');

        return redirect('/courses');
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

        return redirect('/courses');
    }
}
