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
            ->where('revised_at', $programme->wef)
            ->first();

        $semesterCourses = ! $revision ? [] : $revision
            ->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id');

        return view('staff.programmes.revisions.create', [
            'programme' => $programme,
            'revision' => $revision,
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

        if ($programme->wef < $data['revised_at']) {
            $programme->update(['wef' => $data['revised_at']]);
        }

        flash("Programme's revision created successfully!", 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function edit(Programme $programme, ProgrammeRevision $revision)
    {
        if ((int) $revision->programme_id !== (int) $programme->id) {
            return redirect(route('staff.programmes.index'));
        }

        $semesterCourses = $revision->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id');

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

        if ($programme->wef->format('Y-m-d') < $request->revised_at) {
            $programme->update(['wef' => $request->revised_at]);
        }

        flash("Programme's revision edited successfully!", 'success');

        return redirect(route('staff.programmes.revisions.show', $programme));
    }

    public function destroy(Programme $programme, ProgrammeRevision $revision)
    {
        $revision->delete();

        if ($programme->revisions->count() === 0) {
            $programme->delete();
            return redirect(route('staff.programmes.index'));
        }

        $lastRevision = $programme->revisions->max('revised_at');
        $programme->update(['wef' => $lastRevision]);
        return redirect(route('staff.programmes.revisions.show', $programme));
    }
}
