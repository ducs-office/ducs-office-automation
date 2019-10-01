<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::latest()->get();
        return view('courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'min:3', 'max:10', 'unique:courses,code'],
            'name' => ['required', 'min:3', 'max:190'],
        ]);

        Course::create($data);

        flash('Course created successfully!', 'success');
        
        return redirect('/courses');
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:10', 
                Rule::unique('courses')->ignore($course)
            ],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
        ]);

        $course->update($data);
        
        flash('Course updated successfully!', 'success');
        
        return redirect('/courses');
    }

}
