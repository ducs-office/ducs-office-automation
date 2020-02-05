<?php

namespace App\Http\Controllers\Staff;

use App\Programme;
use App\Course;
use App\Events\ProgrammeCreated;
use App\ProgrammeRevision;
use App\CourseProgrammeRevision;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProgrammeRequest;

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
        $programmes = Programme::withLatestRevision()->latest()->get();
        $grouped_courses = $programmes->map(function ($programme) {
            return $programme->latestRevision
                ->courses
                ->sortBy('pivot.semester')
                ->groupBy('pivot.semester');
        });

        return view('staff.programmes.index', [
            'programmes' => $programmes,
            'grouped_courses' => $grouped_courses,
        ]);
    }

    public function create()
    {
        return view('staff.programmes.create', [
            'types' => config('options.programmes.types')
        ]);
    }

    public function store(StoreProgrammeRequest $request)
    {
        $programme = Programme::create($request->validated());

        event(new ProgrammeCreated($programme, $request->semester_courses));

        flash('Programme created successfully!', 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function edit(Programme $programme)
    {
        return view('staff.programmes.edit', [
            'programme' => $programme,
            'types' => config('options.programmes.types'),
        ]);
    }

    public function update(Request $request, Programme $programme)
    {
        $types = implode(',', array_keys(config('options.programmes.types')));

        $data = $request->validate([
            'code' => ['sometimes', 'required', 'min:3', 'max:60',
                        Rule::unique('programmes')->ignore($programme)],
            'wef' => ['sometimes' , 'required', 'date'],
            'type' => ['sometimes', 'required', 'in:'.$types],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
        ]);

        if (isset($data['wef'])) {
            $programme->revisions()->where('revised_at', $programme->wef)->update(['revised_at' => $data['wef']]);

            $latestRevision = $programme->revisions()->max('revised_at');
            $programme->update(['wef' => $latestRevision]);
        }

        $programme->update($request->only(['code', 'name', 'type']));

        flash('Programme updated successfully!', 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function destroy(Programme $programme)
    {
        $programme->revisions->each->delete();

        $programme->delete();

        flash('Programme deleted successfully!', 'success');

        return redirect(route('staff.programmes.index'));
    }
}
