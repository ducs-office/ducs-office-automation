<?php

namespace App\Http\Controllers\Staff;

use App\Events\ProgrammeCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProgrammeRequest;
use App\Http\Requests\Staff\UpdateProgrammeRequest;
use App\Programme;

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
        $groupedCourses = $programmes->map(static function ($programme) {
            return $programme->latestRevision
                ->courses
                ->sortBy('pivot.semester')
                ->groupBy('pivot.semester');
        });

        return view('staff.programmes.index', [
            'programmes' => $programmes,
            'groupedCourses' => $groupedCourses,
            'types' => config('options.programmes.types'),
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

    public function update(UpdateProgrammeRequest $request, Programme $programme)
    {
        if ($request->has('wef')) {
            // @todo: Discuss the need
            $programme->revisions()
                ->where('revised_at', $programme->wef)
                ->update(['revised_at' => $request->wef]);

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
