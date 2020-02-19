<?php

namespace App\Http\Controllers\Teachers;

use App\Exceptions\TeacherProfileNotCompletedException;
use App\Http\Controllers\Controller;
use App\Notifications\TeacherDetailsAccepted;
use App\PastTeachersProfile;
use Illuminate\Http\Request;

class PastTeachersProfilesController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', PastTeachersProfile::class);

        $this->ensureProfileCompleted($request);

        $pastProfile = $request->user()->past_profiles()->create([
            'designation' => $request->user()->profile->designation,
            'college_id' => $request->user()->profile->college_id,
            'valid_from' => PastTeachersProfile::getStartDate(),
        ]);

        $pastProfile->past_teaching_details()
            ->attach($request->user()->profile->teaching_details);

        return $this->sendDetailsSubmittedResponse($request);
    }

    protected function ensureProfileCompleted(Request $request)
    {
        if (! $request->user()->profile->isCompleted()) {
            throw new TeacherProfileNotCompletedException(
                'Your profile is not completed. You cannot perform this action.'
            );
        }
    }

    protected function sendDetailsSubmittedResponse(Request $request)
    {
        $request->user()->notify(new TeacherDetailsAccepted());
        flash('Details submitted successfully!')->success();

        return redirect()->back();
    }
}
