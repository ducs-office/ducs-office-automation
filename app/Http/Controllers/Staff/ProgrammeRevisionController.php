<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProgrammeRevisionRequest;
use App\Http\Requests\Staff\UpdateProgrammeRevisionRequest;
use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use Illuminate\Support\Facades\DB;

class ProgrammeRevisionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProgrammeRevision::class, 'revision');
    }

    public function index(Programme $programme)
    {
        $programme->load(['revisions.courses']);

        $groupedCourses = $programme->revisions
            ->map(static function ($revision) {
                return $revision->courses->groupBy('pivot.semester');
            });

        return view('staff.programmes.revisions.index', [
            'programme' => $programme,
            'groupedRevisionCourses' => $groupedCourses,
        ]);
    }

    public function create(Programme $programme)
    {
        $courses = Course::where('code', 'like', "{$programme->code}%")->get();
        $revision = $programme->revisions()
            ->with('courses')
            ->first();

        $semesterCourses = ! $revision ? [] : $revision
            ->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id')->toArray();

        return view('staff.programmes.revisions.create', [
            'programme' => $programme,
            'semesterCourses' => $semesterCourses,
            'courses' => $courses,
        ]);
    }

    public function store(StoreProgrammeRevisionRequest $request, Programme $programme)
    {
        $data = $request->validated();

        $revision = ProgrammeRevision::create([
            'revised_at' => $data['revised_at'],
            'programme_id' => $programme->id,
        ]);

        $revision->courses()->sync($request->getSemesterCourses());

        flash("Programme's revision created successfully!", 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function edit(Programme $programme, ProgrammeRevision $revision)
    {
        $semesterCourses = $revision->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id')
            ->toArray();

        return view('staff.programmes.revisions.edit', [
            'programme' => $programme,
            'revision' => $revision,
            'semesterCourses' => $semesterCourses,
            'courses' => Course::where('code', 'like', "{$programme->code}%")->get(),
        ]);
    }

    public function update(UpdateProgrammeRevisionRequest $request, Programme $programme, ProgrammeRevision $revision)
    {
        DB::beginTransaction();

        $revision->update($request->validated());
        $revision->courses()->sync($request->getSemesterCourses());

        DB::commit();

        flash("Programme's revision edited successfully!", 'success');

        return redirect(route('staff.programmes.revisions.show', $programme));
    }

    public function destroy(Programme $programme, ProgrammeRevision $revision)
    {
        $revision->delete();

        return redirect(route('staff.programmes.revisions.show', $programme));
    }
}
