<?php

namespace App\Http\Controllers;

use App\Course;
use App\Paper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $papers = Paper::latest()->get();
        $courses = Course::all()->pluck('name', 'id');

        return view('papers.index', compact('papers', 'courses'));
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
            'code' => ['required', 'min:3', 'max:10', 'unique:papers'],
            'name' => ['required', 'min:3', 'max:190'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        Paper::create($data);

        flash('Paper created successfully!', 'success');
        
        return redirect('/papers');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paper $paper)
    {
        $data = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:10', 
                Rule::unique('papers')->ignore($paper)
            ],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'course_id' => ['sometimes', 'required', 'integer', 'exists:courses,id'],
        ]);

        $paper->update($data);

        flash('Paper updated successfully!', 'success');
        
        return redirect('/papers');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Paper  $paper
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paper $paper)
    {
        $paper->delete();

        flash('Paper deleted successfully!', 'success');

        return redirect('/papers');
    }
}
