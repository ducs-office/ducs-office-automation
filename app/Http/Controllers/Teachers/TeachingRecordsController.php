<?php

namespace App\Http\Controllers\Teachers;

use App\Exceptions\TeacherProfileNotCompleted;
use App\Http\Controllers\Controller;
use App\Notifications\TeacherDetailsAccepted;
use App\Notifications\TeachingRecordsSaved;
use App\TeachingRecord;
use Illuminate\Http\Request;

class TeachingRecordsController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', TeachingRecord::class);

        $this->ensureProfileCompleted($request);
        $currentProfile = $request->user()->profile;

        $request->user()->teachingRecords()->createMany(
            array_map(function ($detail) use ($currentProfile) {
                return ['valid_from' => TeachingRecord::getStartDate()]
                    + $currentProfile->only(['college_id', 'designation', 'teacher_id'])
                    + $detail->only(['programme_revision_id', 'course_id', 'semester']);
            }, $currentProfile->teachingDetails()->get()->all())
        );

        return $this->sendDetailsSubmittedResponse($request);
    }

    protected function ensureProfileCompleted(Request $request)
    {
        if (! $request->user()->profile->isCompleted()) {
            throw new TeacherProfileNotCompleted(
                'Your profile is not completed. You cannot perform this action.'
            );
        }
    }

    protected function sendDetailsSubmittedResponse(Request $request)
    {
        $request->user()->notify(new TeachingRecordsSaved());

        flash('Details submitted successfully!')->success();

        return redirect()->back();
    }
}
