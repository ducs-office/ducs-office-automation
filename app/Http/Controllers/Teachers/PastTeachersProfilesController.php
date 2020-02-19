<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Notifications\TeacherDetailsAccepted;
use App\PastTeachersProfile;
use Illuminate\Http\Request;

class PastTeachersProfilesController extends Controller
{
    public function store()
    {
        $this->authorize('create', PastTeachersProfile::class);

        $teacher = auth()->user();
        $teacherProfile = $teacher->profile;

        if (! $teacherProfile->designation
            || ! $teacherProfile->college_id
            || ! $teacherProfile->teaching_details->count()
        ) {
            flash('Fill complete details to make submission', 'fail');
            return redirect()->back();
        }

        $pastProfile = $teacher->past_profiles()->create(
            [
                'designation' => $teacherProfile->designation,
                'college_id' => $teacherProfile->college_id,
                'valid_from' => PastTeachersProfile::getStartDate(),
            ]
        );

        $pastProfile->past_teaching_details()
            ->attach($teacherProfile->teaching_details->pluck('id')->toArray());

        flash('Details submitted successfully!', 'success');

        $teacher->notify(new TeacherDetailsAccepted());

        return redirect()->back();
    }
}
