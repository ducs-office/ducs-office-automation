<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

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
            'code' => ['required', 'min:3', 'max:10'],
            'name' => ['required', 'min:3', 'max:190'],
        ]);

        Course::create($data);

        flash('Course created successfully!', 'success');
        
        return redirect('/courses');
    }

}
