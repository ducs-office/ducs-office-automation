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
        $courses = Course::where('programme_id', null)->get();
        return view('programmes.index', compact('programmes', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'min:3', 'max:60', 'unique:programmes,code'],
            'wef' => ['required', 'date'],
            'name' => ['required', 'min:3', 'max:190'],
            'courses' => ['nullable', 'array', 'min:1'],
            'courses.*' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $programme = Programme::create([
            'code' => $data['code'],
            'wef' => $data['wef'],
            'name' => $data['name'],
        ]);
        
        if($request->has('courses')) {
            Course::whereIn('id', $data['courses'])->update(['programme_id' => $programme->id]);
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
            'courses' => ['nullable', 'array', 'min:1'],
            'courses.*' => ['sometimes', 'required', 'integer', 'exists:courses,id'],
        ]);

        $programme->update($request->only(['code', 'wef', 'name']));
        Course::where('programme_id', $programme->id)->update(['programme_id' => null]);
        
        if ($request->has('courses')) {
            Course::whereIn('id', $data['courses'])->update(['programme_id' => $programme->id]);
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
