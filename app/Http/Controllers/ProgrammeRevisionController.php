<?php

namespace App\Http\Controllers;

use App\Programme;
use App\Course;
use App\ProgrammeRevision;
use App\CourseProgrammeRevision;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ProgrammeRevisionController extends Controller
{
    public function index(Programme $programme)
    {
        $programmeRevisions = $programme->revisions->sortByDesc('revised_at');

        $groupedRevisionCourses = $programmeRevisions->map(function ($programmeRevision) {
            return $programmeRevision->courses->sortBy('pivot.semester')->groupBy('pivot.semester');
        });

        return view('staff.programmes.revisions.index', compact('programme', 'programmeRevisions', 'groupedRevisionCourses'));
    }

    public function create(Programme $programme)
    {
        $programme_revision = $programme->revisions()->where('revised_at', $programme->wef)->first();
        $semester_courses = $programme_revision->courses->groupBy('pivot.semester')->map->pluck('id');
        return view('staff.programmes.revisions.create', compact('programme', 'semester_courses'));
    }

    public function store(Programme $programme, Request $request)
    {
        $data = $request->validate([
            'revised_at' => ['required', 'date',
                function ($attribute, $value, $fail) use ($programme) {
                    $revisions = $programme->revisions->map->toArray();
                    if ($revisions->contains('revised_at', $value)) {
                        $fail($attribute.' is invalid');
                    }
                },
            ],
            'semester_courses' => [
                'sometimes', 'required', 'array',
                'size:'.(($programme->duration) * 2),
            ],
            'semester_courses.*' => ['required', 'array', 'min:1'],
            'semester_courses.*.*' => ['numeric', 'distinct', 'exists:courses,id',
                function ($attribute, $value, $fail) use ($programme) {
                    $courses = CourseProgrammeRevision::all();
                    foreach ($courses as $course) {
                        if ($value == $course->course_id && Course::find($course->course_id)->programme_revisions()->first()->programme_id != $programme->id) {
                            $fail($attribute.' is invalid');
                        }
                    }
                },
            ],
        ]);

        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $data['revised_at'], 'programme_id' => $programme->id]);

        foreach ($data['semester_courses'] as $semester => $courses) {
            foreach ($courses as $course) {
                Course::find($course)->programme_revisions()->attach($revision, ['semester' => $semester + 1]);
            }
        }

        if ($programme->wef < $data['revised_at']) {
            $programme->update(['wef' => $data['revised_at']]);
        }

        flash("Programme's revision created successfully!", 'success');

        return redirect('/programmes');
    }

    public function edit(Programme $programme, ProgrammeRevision $programme_revision)
    {
        if ($programme_revision->programme_id == $programme->id) {
            $semester_courses = $programme_revision->courses->groupBy('pivot.semester')->map->pluck('id');
            return view('staff.programmes.revisions.edit', compact('programme', 'programme_revision', 'semester_courses'));
        } else {
            return redirect('/programmes');
        }
    }

    public function update(Programme $programme, ProgrammeRevision $programme_revision, Request $request)
    {
        $data = $request->validate([
            'revised_at' => ['sometimes', 'required', 'date',
                function ($attribute, $value, $fail) use ($programme, $programme_revision) {
                    $revisions = $programme->revisions
                        ->filter(function ($revision) use ($programme_revision) {
                            return $revision->id != $programme_revision->id;
                        })
                        ->map->toArray();
                    if ($revisions->contains('revised_at', $value)) {
                        $fail($attribute.' is invalid');
                    }
                },
            ],
            'semester_courses' => [
                'sometimes', 'required', 'array',
                'size:'.(($programme->duration) * 2),
            ],
            'semester_courses.*' => ['sometimes', 'required', 'array', 'min:1'],
            'semester_courses.*.*' => ['sometimes', 'numeric', 'distinct', 'exists:courses,id',
                function ($attribute, $value, $fail) use ($programme) {
                    $courses = CourseProgrammeRevision::all();
                    foreach ($courses as $course) {
                        if ($value == $course->course_id && Course::find($course->course_id)->programme_revisions()->first()->programme_id != $programme->id) {
                            $fail($attribute.'is invalid');
                        }
                    }
                },
            ],
        ]);

        $programme_revision->update($data);

        $semester_courses = collect($request->semester_courses)
            ->map(function ($courses, $index) {
                return array_map(function ($course) use ($index) {
                    return [$course, $index + 1];
                }, $courses);
            })->flatten(1)->pluck('1', '0')
            ->map(function ($value) {
                return ['semester' => $value];
            })->toArray();

        $programme_revision->courses()->sync($semester_courses);

        if ($programme->wef->format('Y-m-d') < $data['revised_at']) {
            $programme->update(['wef' => $data['revised_at']]);
        }

        flash("Programme's revision edited successfully!", 'success');

        return redirect("/programme/$programme->id/revision");
    }

    public function destroy(Programme $programme, ProgrammeRevision $programmeRevision)
    {
        $programmeRevision->delete();

        if ($programme->revisions->count() == 0) {
            $programme->delete();
            return redirect("/programmes");
        } else {
            $lastRevision = $programme->revisions->max('revised_at');
            $programme->update(['wef' => $lastRevision]);
            return redirect("/programme/{$programme->id}/revision");
        }
    }
}
