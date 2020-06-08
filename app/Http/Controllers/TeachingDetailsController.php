<?php

namespace App\Http\Controllers;

use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\ProgrammeRevision;
use App\Models\TeachingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeachingDetailsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TeachingDetail::class, 'teachingDetail');
    }

    public function index(Request $request)
    {
        return view('teaching-details.index', [
            'currentTeachingDetails' => $request->user()->teachingDetails,
            'oldTeachingRecords' => $request->user()
                ->teachingRecords
                ->groupBy(function ($record) {
                    return $record->valid_from->format('M, Y');
                }),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'programme_revision_id' => ['required', 'numeric'],
            'course_id' => ['required', 'numeric'],
        ]);

        $courseProgrammeRevision = CourseProgrammeRevision::query()
            ->whereProgrammeRevisionId($request->programme_revision_id)
            ->whereCourseId($request->course_id)
            ->first();

        if ($courseProgrammeRevision == null) {
            return redirect()->back()
                ->withErrors(['course_id' => 'Course does not belong to given Programme.'])
                ->withInput($data);
        }

        $request->user()->teachingDetails()->create(
            $courseProgrammeRevision->only([
                'programme_revision_id',
                'course_id', 'semester',
            ])
        );

        flash()->success('Teaching detail added!');

        return redirect()->back();
    }

    public function destroy(TeachingDetail $teachingDetail)
    {
        $teachingDetail->delete();

        flash()->success('Teaching detail removed!');

        return redirect()->back();
    }
}
