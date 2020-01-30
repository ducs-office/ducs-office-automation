<?php

namespace App\Http\Controllers\Teachers;

use App\CourseProgrammeRevision;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Programme;
use App\TeacherProfile;

class ProfileController extends Controller
{
    public function index()
    {
        return view('teachers.profile', [
            'teacher' => auth()->user()
        ]);
    }

    public function edit()
    {
        return view('teachers.edit', [
            'teacher' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $teacher = auth()->user();

        $designations = implode(',', array_keys(config('options.teachers.designation')));

        $validData = $request->validate([
            'phone_no' => 'nullable|string|',
            'address' => 'nullable|string|',
            'designation' => 'nullable|string|in:'.$designations,
            'ifsc' => 'nullable|string|',
            'account_no' => 'nullable|string|',
            'bank_name' => 'nullable|string|',
            'bank_branch' => 'nullable|string|',
            'college_id' => 'nullable| numeric',
            'teaching_details' => 'nullable| array',
            'teaching_details.*.0' => [ 'nullable', 'numeric', 'exists:programmes,id',
                function ($attribute, $value, $fail) {
                    $programme = Programme::find($value);
                    if ($programme->latestRev()->revised_at != $programme->wef) {
                        $fail($attribute. ' is invalid.');
                    }
                }
            ],
            'teaching_details.*.1' => ['nullable', 'numeric', 'exists:courses,id'],
            'teaching_details.*' => ['bail', 'nullable', 'array', 'size:2', 'distinct',
                function ($attribute, $value, $fail) {
                    $programme = Programme::find($value[0]);
                    if ($programme->latestRev()->courses->map->id->contains($value[1]) == false) {
                        $fail($attribute. ' is invalid.');
                    }
                }
            ],
        ]);
        
        if ($teacher->profile()->exists()) {
            $teacherProfile = $teacher->profile;
            $teacherProfile->update($validData);
        } else {
            TeacherProfile::create($validData + ['teacher_id' => $teacher->id]);
        }

        if (isset($validData['teaching_details'])) {
            $teaching_details = $validData['teaching_details'];

            $programmeCoursesTaught = array_map(function ($teaching_detail) {
                return CourseProgrammeRevision::where('programme_revision_id', $teaching_detail[0])
                            ->where('course_id', $teaching_detail[1])->first()->id;
            }, $teaching_details);

            $teacher->profile->teaching_details()->sync($programmeCoursesTaught);
        }

        flash('Profile Updated Successfully!')->success();

        return redirect(route('teachers.profile'));
    }
}
