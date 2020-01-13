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
        $grouped_courses = $programmes->map(function ($programme) {
            return $programme->courses->groupBy('pivot.semester');
        });

        return view('programmes.index', compact('programmes', 'grouped_courses'));
    }

    public function create()
    {
        return view('programmes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'min:3', 'max:60', 'unique:programmes,code'],
            'wef' => ['required', 'date'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', 'in:Under Graduate(U.G.),Post Graduate(P.G.)'],
            'duration' => ['required', 'integer'],
            'semester_courses' => ['required', 'array', 'size:'.($request->duration * 2) ],
            'semester_courses.*' => ['required', 'array', 'min:1'],
            'semester_courses.*.*' => ['numeric', 'exists:courses,id', 'unique:course_programme,course_id']
        ]);

        $programme = Programme::create([
            'code' => $data['code'],
            'wef' => $data['wef'],
            'name' => $data['name'],
            'type' => $data['type'],
            'duration' => $data['duration']
        ]);
        
        foreach ($request->semester_courses as $index => $courses) {
            $programme->courses()->attach($courses, ['semester' => $index + 1]);
        }
        
        flash('Programme created successfully!', 'success');

        return redirect('/programmes');
    }

    public function edit(Programme $programme)
    {
        return view('programmes.edit', compact('programme'));
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
            'duration' => ['sometimes', 'required', 'integer'],
            'semester_courses' => ['sometimes', 'required', 'array', 'size:'.($request->duration * 2) ],
            'semester_courses.*' => ['required', 'array', 'min:1'],
            'semester_courses.*.*' => ['numeric', 'exists:courses,id',
                Rule::unique('course_programme', 'course_id')->ignore($programme->id, 'programme_id'),
            ],
        ]);

        $programme->update($request->only(['code', 'wef', 'name', 'type', 'duration']));

        $semester_courses = collect($request->semester_courses)
            ->map(function ($courses, $index) {
                return array_map(function ($course) use ($index) {
                    return [$course, $index + 1];
                }, $courses);
            })->flatten(1)->pluck('1', '0')
            ->map(function ($value) {
                return ['semester' => $value];
            })->toArray();

        $programme->courses()->sync($semester_courses);

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
