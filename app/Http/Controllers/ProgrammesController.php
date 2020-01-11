<?php

namespace App\Http\Controllers;

use App\Programme;
use App\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgrammesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Programme::class, 'programme');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programmes = Programme::latest()->with(['courses'])->get();
        $courses = Course::doesntHave('programmes')->get();
        return view('programmes.index', compact('programmes', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'min:3', 'max:60', 'unique:programmes,code'],
            'wef' => ['required', 'date'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', 'in:Under Graduate(U.G.),Post Graduate(P.G.)'],
            'courses' => ['nullable', 'array', 'min:1'],
            'courses.*' => ['required', 'integer', 'exists:courses,id', 'unique:course_programme,course_id'],
            'duration' => ['required', 'integer'],
        ]);

        $programme = Programme::create([
            'code' => $data['code'],
            'wef' => $data['wef'],
            'name' => $data['name'],
            'type' => $data['type'],
            'duration' => $data['duration']
        ]);
        
        if ($request->has('courses')) {
            $programme->courses()->attach($data['courses']);
        }
        
        flash('Programme created successfully!', 'success');

        return redirect('/programmes');
    }

    public function update(Request $request, Programme $programme)
    {
        $data = $request->validate([
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('programmes')->ignore($programme)
            ],
            'wef' => ['sometimes', 'required', 'date'],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'type' => ['sometimes', 'required', 'in:Under Graduate(U.G.),Post Graduate(P.G.)'],
            'courses' => ['nullable', 'array', 'min:1'],
            'courses.*' => ['sometimes', 'required', 'integer', 'exists:courses,id',
                             Rule::unique('course_programme', 'course_id')->ignore($programme->id, 'programme_id'),
                        ],
            'duration' => ['sometimes', 'required', 'integer'],
        ]);

        $programme->update($request->only(['code', 'wef', 'name', 'type', 'duration']));

        if ($request->has('courses')) {
            $programme->courses()->sync($data['courses']);
        }

        flash('Programme updated successfully!', 'success');

        return redirect('/programmes');
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();

        flash('Programme deleted successfully!', 'success');

        return redirect('/programmes');
    }
}
