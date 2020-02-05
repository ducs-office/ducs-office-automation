<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Programme;
use App\Course;
use App\ProgrammeRevision;
use App\Http\Requests\Staff\StoreProgrammeRevisionRequest;
use App\Http\Requests\Staff\UpdateProgrammeRevisionRequest;

class ProgrammeRevisionController extends Controller
{
    public function index(Programme $programme)
    {
        $programme_revisions = $programme->revisions->sortByDesc('revised_at');

        $grouped_revision_courses = $programme_revisions
            ->map(function ($revisions) {
                return $revisions->courses
                    ->sortBy('pivot.semester')
                    ->groupBy('pivot.semester');
            });

        return view('staff.programmes.revisions.index', [
            'programme' => $programme,
            'programmeRevisions' => $programme_revisions,
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


        $semester_courses = (! $revision) ? [] : $revision
            ->courses
            ->groupBy('pivot.semester')
            ->map
            ->pluck('id');


        return view('staff.programmes.revisions.create', [
            'programme' => $programme,
            'revision' => $revision,
            'semester_courses' =>  $semester_courses,
            'courses' => $courses
        ]);
    }

    public function store(StoreProgrammeRevisionRequest $request, Programme $programme)
    {
        $data = $request->validated();

        $revision = ProgrammeRevision::create([
            'revised_at' => $data['revised_at'],
            'programme_id' => $programme->id
        ]);

        $semester_courses = collect($request->semester_courses)
            ->map(function ($courses, $semester) {
                return array_map(function ($course) use ($semester) {
                    return ['id' => $course, 'pivot' => ['semester' => $semester]];
                }, $courses);
            })->flatten(1)->pluck('pivot', 'id')
            ->toArray();

        $revision->courses()->sync($semester_courses);

        if ($programme->wef < $data['revised_at']) {
            $programme->update(['wef' => $data['revised_at']]);
        }

        flash("Programme's revision created successfully!", 'success');

        return redirect(route('staff.programmes.index'));
    }

    public function edit(Programme $programme, ProgrammeRevision $programme_revision)
    {
        if ($programme_revision->programme_id != $programme->id) {
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
        $programme_revision->update($request->validated());

        $semester_courses = collect($request->semester_courses)
            ->map(function ($courses, $semester) {
                return array_map(function ($course) use ($semester) {
                    return ['id' => $course, 'pivot' => ['semester' => $semester]];
                }, $courses);
            })->flatten(1)->pluck('pivot', 'id')
            ->toArray();

        $programme_revision->courses()->sync($semester_courses);

        if ($programme->wef->format('Y-m-d') < $request->revised_at) {
            $programme->update(['wef' => $request->revised_at]);
        }

        flash("Programme's revision edited successfully!", 'success');

        return redirect(route('staff.programmes.revisions.show', $programme));
    }

    public function destroy(Programme $programme, ProgrammeRevision $programmeRevision)
    {
        $programmeRevision->delete();

        if ($programme->revisions->count() == 0) {
            $programme->delete();
            return redirect(route('staff.programmes.index'));
        }

        $lastRevision = $programme->revisions->max('revised_at');
        $programme->update(['wef' => $lastRevision]);
        return redirect(route('staff.programmes.revisions.show', $programme));
    }
}
