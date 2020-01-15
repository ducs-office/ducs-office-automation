<?php

namespace App\Http\Controllers;

use App\Programme;
use App\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

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
        $programmes = Programme::latest()->with([
            'courses' => function ($q) {
                return $q->orderBy('semester');
            }
        ])->get();

        $programmes->map(function ($programme) {
            $programme->courses =  $programme->courses->filter(function ($programme_course) use ($programme) {
                return ($programme_course['pivot']['revised_on'] == $programme->wef);
            });
            return $programme;
        });

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
            'semester_courses.*.*' => ['numeric', 'distinct', 'exists:courses,id', 'unique:course_programme,course_id']
        ]);

        $programme = Programme::create([
            'code' => $data['code'],
            'wef' => $data['wef'],
            'name' => $data['name'],
            'type' => $data['type'],
            'duration' => $data['duration']
        ]);

        foreach ($request->semester_courses as $index => $courses) {
            $programme->courses()->attach($courses, ['semester' => $index + 1, 'revised_on' => $data['wef']]);
        }

        Cache::put($programme->code.'lastRevision', 'Null');

        flash('Programme created successfully!', 'success');

        return redirect('/programmes');
    }

    public function edit(Programme $programme)
    {
        return view('programmes.edit', compact('programme'));
    }

    public function update(Request $request, Programme $programme)
    {
        $lastRevision = Cache::get($programme->code.'lastRevision');
        
        if ($lastRevision == null) {
            $lastRevision = '0000-00-00';
        }
        
        $data = $request->validate([
            'code' => ['sometimes', 'required', 'min:3', 'max:60',
                        Rule::unique('programmes')->ignore($programme)],
            'wef' => ['sometimes' , 'required', 'date', 'after:'.$lastRevision],
            'type' => ['sometimes', 'required', 'in:Under Graduate(U.G.),Post Graduate(P.G.)'],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
        ]);

        $programme->update($request->only(['code', 'wef', 'name', 'type']));

        flash('Programme updated successfully!', 'success');

        return redirect('/programmes');
    }

    public function upgrade(Programme $programme)
    {
        $semester_courses = $programme->courses->where('pivot.revised_on', $programme->wef)->groupBy('pivot.semester');
        return view('programmes.upgrade', compact('programme', 'semester_courses'));
    }

    public function revision(Programme $programme, Request $request)
    {
        $data = $request->validate([
            'revised_on' => ['required', 'date', 'after:'.$programme->wef],
            'semester_courses' => [
                'sometimes', 'required', 'array',
                'size:'.(($programme->duration) * 2),
            ],
            'semester_courses.*' => ['required', 'array', 'min:1'],
            'semester_courses.*.*' => ['numeric', 'distinct', 'exists:courses,id',
                Rule::unique('course_programme', 'course_id')->ignore($programme->id, 'programme_id'),
            ],
        ]);
        
        Cache::put($programme->code.'lastRevision', $programme->wef);
       
        $revised_on = $data['revised_on'];

        $semester_courses = collect($request->semester_courses)
        ->map(function ($courses, $index) {
            return array_map(function ($course) use ($index) {
                return [$course, $index + 1];
            }, $courses);
        })->flatten(1)->pluck('1', '0')
        ->map(function ($value) use ($revised_on) {
            return ['semester' => $value, 'revised_on' => $revised_on];
        })->toArray();

        $programme->update(['wef' => $data['revised_on']]);
        $programme->courses()->attach($semester_courses);

        flash('Programme revised successfully!', 'success');

        return redirect('/programmes');
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();

        flash('Programme deleted successfully!', 'success');

        return redirect('/programmes');
    }
}
