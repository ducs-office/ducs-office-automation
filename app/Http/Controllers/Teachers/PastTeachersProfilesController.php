<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PastTeachersProfilesController extends Controller
{
    public function store()
    {
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
                'valid_from' => now()->format('d-m-Y'),
            ]
        );

        $pastProfile->past_teaching_details()->createMany(
            array_map(function ($teaching_detail) {
                return ['course_programme_revision_id' => $teaching_detail['id']];
            }, $teacherProfile->teaching_details->toArray())
        );

        flash('Details submitted successfully!', 'success');
        return redirect()->back();
    }
}
