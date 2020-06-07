<?php

namespace App\Http\Controllers\Staff;

use App\Events\ProgrammeCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProgrammeRequest;
use App\Http\Requests\Staff\UpdateProgrammeRequest;
use App\Models\Programme;
use App\Types\ProgrammeType;

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
            'types' => ProgrammeType::values(),
        ]);
    }

    public function create()
    {
        return view('staff.programmes.create', [
            'types' => ProgrammeType::values(),
        ]);
    }

    public function store(StoreProgrammeRequest $request)
    {
        $programme = Programme::create($request->validated());

        $request->createProgrammeRevision($programme);

        flash('Programme created successfully!', 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function edit(Programme $programme)
    {
        return view('staff.programmes.edit', [
            'programme' => $programme,
            'types' => ProgrammeType::values(),
        ]);
    }

    public function update(UpdateProgrammeRequest $request, Programme $programme)
    {
        $programme->update($request->validated());

        flash('Programme updated successfully!', 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();

        flash('Programme deleted successfully!', 'success');

        return redirect(route('staff.programmes.index'));
    }
}
