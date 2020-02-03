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
            'phone_no' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'designation' => ['nullable', 'string','in:'.$designations],
            'ifsc' => ['nullable', 'string'],
            'account_no' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string'],
            'bank_branch' => ['nullable', 'string'],
            'college_id' => ['nullable', 'numeric', 'exists:colleges,id'],
            'teaching_details' => ['nullable' , 'array'],
            'teaching_details.*.programme' => ['nullable', 'numeric', 'exists:programmes,id',
                function ($attribute, $value, $fail) {
                    $programme = Programme::find($value);
                    if ($programme->latestRev()->revised_at != $programme->wef) {
                        $fail($attribute. ' is invalid.');
                    }
                }
            ],
            'teaching_details.*.course' => ['nullable', 'numeric', 'exists:courses,id'],
            'teaching_details.*' => ['bail', 'nullable', 'array', 'size:2', 'distinct',
                function ($attribute, $value, $fail) {
                    $programme = Programme::find($value['programme']);
                    if ($programme->latestRev()->courses->map->id->contains($value['course']) == false) {
                        $fail($attribute. ' is invalid.');
                    }
                }
            ],
            'profile_picture' => ['nullable', 'file', 'image'],
        ]);
        
        if ($teacher->profile()->exists()) {
            $teacherProfile = $teacher->profile;
            $teacherProfile->update($validData);
        } else {
            $teacherProfile = TeacherProfile::create($validData + ['teacher_id' => $teacher->id]);
        }

        if (isset($validData['teaching_details'])) {
            $teaching_details = $validData['teaching_details'];

            $programmeCoursesTaught = array_map(function ($teaching_detail) {
                return CourseProgrammeRevision::where('programme_revision_id', $teaching_detail['programme'])
                            ->where('course_id', $teaching_detail['course'])->first()->id;
            }, $teaching_details);

            $teacher->profile->teaching_details()->sync($programmeCoursesTaught);
        }

        if (isset($validData['profile_picture'])) {
            $teacherProfile->profile_picture()->create([
                'original_name' => $validData['profile_picture']->getClientOriginalName(),
                'path' => $validData['profile_picture']->store('/teacher_attachments/profile_picture'),
            ]);
        }

        flash('Profile Updated Successfully!')->success();

        return redirect(route('teachers.profile'));
    }
}
