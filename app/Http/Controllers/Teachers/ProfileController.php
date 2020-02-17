<?php

namespace App\Http\Controllers\Teachers;

use App\College;
use App\Course;
use App\CourseProgrammeRevision;
use App\Http\Controllers\Controller;
use App\Programme;
use App\ProgrammeRevision;
use App\Teacher;
use App\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('teachers.profile', [
            'teacher' => auth()->user()->load([
                'profile.college',
                'profile.teaching_details',
                'profile.profile_picture',
            ]),
            'designations' => config('options.teachers.designations'),
        ]);
    }

    public function edit()
    {
        return view('teachers.edit', [
            'teacher' => auth()->user()->load([
                'profile.college',
                'profile.teaching_details',
                'profile.profile_picture',
            ]),
            'colleges' => College::all()->pluck('name', 'id'),
            'programmes' => Programme::withLatestRevision()->get()->map(function ($programme) {
                return [
                    'id' => $programme->latestRevision->id,
                    'name' => $programme->code . ' - ' . $programme->name,
                ];
            })->pluck('name', 'id'),
            'courses' => Course::all()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->code . ' - ' . $course->name,
                ];
            })->pluck('name', 'id'),
            'designations' => config('options.teachers.designations'),
        ]);
    }

    public function update(Request $request)
    {
        $teacher = auth()->user();

        $designations = implode(',', array_keys(config('options.teachers.designations')));

        $validData = $request->validate([
            'phone_no' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'designation' => ['nullable', 'string', 'in:' . $designations],
            'ifsc' => ['nullable', 'string'],
            'account_no' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string'],
            'bank_branch' => ['nullable', 'string'],
            'college_id' => ['nullable', 'numeric', 'exists:colleges,id'],
            'teaching_details' => ['nullable', 'array'],
            'teaching_details.*.programme_revision' => ['nullable', 'numeric', 'exists:programme_revisions,id'],
            'teaching_details.*.course' => ['nullable', 'numeric', 'exists:courses,id'],
            'teaching_details.*' => ['bail', 'nullable', 'array',
                function ($attribute, $value, $fail) {
                    if (! isset($value['course'])) {
                        return true;
                    }
                    $revision = ProgrammeRevision::find($value['programme_revision']);
                    if ($revision->courses->pluck('id')->contains($value['course']) == false) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
            'profile_picture' => ['nullable', 'file', 'image'],
        ]);

        $teacher->profile->update($validData);

        if (isset($validData['teaching_details'])) {
            $teaching_details = $validData['teaching_details'];

            $programmeCoursesTaught = collect($teaching_details)
                ->filter(function ($teaching_detail) {
                    return isset($teaching_detail['programme_revision'], $teaching_detail['course']);
                })->map(function ($teaching_detail) {
                    return CourseProgrammeRevision::where(
                        'programme_revision_id',
                        $teaching_detail['programme_revision']
                    )
                        ->where('course_id', $teaching_detail['course'])
                        ->first()->id;
                })->toArray();

            $teacher->profile->teaching_details()->sync($programmeCoursesTaught);
        }

        if (isset($validData['profile_picture'])) {
            $teacher->profile->profile_picture()->create([
                'original_name' => $validData['profile_picture']->getClientOriginalName(),
                'path' => $validData['profile_picture']->store('/teacher_attachments/profile_picture'),
            ]);
        }

        flash('Profile Updated Successfully!')->success();

        return redirect(route('teachers.profile'));
    }

    public function avatar()
    {
        $attachmentPicture = auth()->user()->profile->profile_picture;

        if ($attachmentPicture && Storage::exists($attachmentPicture->path)) {
            return Response::file(Storage::path($attachmentPicture->path));
        }

        $gravatarHash = md5(strtolower(trim(auth()->user()->email)));
        $avatar = file_get_contents('https://gravatar.com/avatar/' . $gravatarHash . '?s=200&d=identicon');

        return Response::make($avatar, 200, [
            'Content-Type' => 'image/jpg',
        ]);
    }
}
