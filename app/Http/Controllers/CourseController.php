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
        $courses = Course::latest()->get();
        $programmes = Programme::all()->pluck('name', 'id');

        return view('courses.index', compact('courses', 'programmes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'min:3', 'max:10', 'unique:courses'],
            'name' => ['required', 'min:3', 'max:190'],
            'programme_id' => ['required', 'integer', 'exists:programmes,id'],
        ]);

        Course::create($data);

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
                'sometimes', 'required', 'min:3', 'max:10',
                Rule::unique('courses')->ignore($course)
            ],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'programme_id' => ['sometimes', 'required', 'integer', 'exists:programmes,id'],
        ]);

        $course->update($data);

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
