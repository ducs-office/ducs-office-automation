<?php

namespace App\Http\Controllers\Staff;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProgrammeRevisionRequest;
use App\Http\Requests\Staff\UpdateProgrammeRevisionRequest;
use App\Programme;
use App\ProgrammeRevision;
use Illuminate\Support\Facades\DB;

class ProgrammeRevisionController extends Controller
{
    public function index(Programme $programme)
    {
        $programme->load(['revisions.courses']);

        $grouped_revision_courses = $programme->revisions
            ->map(static function ($revision) {
                return $revision->courses->groupBy('pivot.semester');
            });

        return view('staff.programmes.revisions.index', [
            'programme' => $programme,
            'groupedRevisionCourses' => $grouped_revision_courses,
        ]);
    }

    public function create(Programme $programme)
    {
        $courses = Course::where('code', 'like', "{$programme->code}%")->get();
        $revision = $programme->revisions()
            ->with('courses')
            ->where('revised_at', $programme->wef)
            ->first();

        $semester_courses = ! $revision ? [] : $revision
            ->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id');

        return view('staff.programmes.revisions.create', [
            'programme' => $programme,
            'revision' => $revision,
            'semester_courses' => $semester_courses,
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

    public function edit(Programme $programme, ProgrammeRevision $programme_revision)
    {
        if ((int) $programme_revision->programme_id !== (int) $programme->id) {
            return redirect(route('staff.programmes.index'));
        }

        $semester_courses = $programme_revision->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id');

        return view('staff.programmes.revisions.edit', [
            'programme' => $programme,
            'programme_revision' => $programme_revision,
            'semester_courses' => $semester_courses,
            'courses' => Course::where('code', 'like', "{$programme->code}%")->get(),
        ]);
    }

    public function update(UpdateProgrammeRevisionRequest $request, Programme $programme, ProgrammeRevision $programme_revision)
    {
        DB::beginTransaction();

        $programme_revision->update($request->validated());
        $programme_revision->courses()->sync($request->getSemesterCourses());

        DB::commit();

        if ($programme->wef->format('Y-m-d') < $request->revised_at) {
            $programme->update(['wef' => $request->revised_at]);
        }

        flash("Programme's revision edited successfully!", 'success');

        return redirect(route('staff.programmes.revisions.show', $programme));
    }

    public function destroy(Programme $programme, ProgrammeRevision $programmeRevision)
    {
        $programmeRevision->delete();

        if ($programme->revisions->count() === 0) {
            $programme->delete();
            return redirect(route('staff.programmes.index'));
        }

        $lastRevision = $programme->revisions->max('revised_at');
        $programme->update(['wef' => $lastRevision]);
        return redirect(route('staff.programmes.revisions.show', $programme));
    }
}
