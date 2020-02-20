<?php

namespace App\Http\Controllers\Teachers;

use App\College;
use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdateProfileRequest;
use App\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->load([
            'teachingRecords',
            'profile.college',
            'profile.profilePicture',
            'profile.teachingDetails',
        ]);

        return view('teachers.profile', [
            'designations' => config('options.teachers.designations'),
            'teacher' => $teacher,
        ]);
    }

    public function edit(Request $request)
    {
        $teacher = $request->user()->load([
            'profile.college',
            'profile.profilePicture',
            'profile.teachingDetails',
        ]);

        $programmes = Programme::withLatestRevision()->get()
            ->mapWithKeys(static function ($programme) {
                $latestRevisionId = $programme->latestRevision->id;
                $name = $programme->code . ' - ' . $programme->name;
                return [$latestRevisionId => $name];
            });

        $courses = Course::select(['id', 'code', 'name'])->get()
            ->mapWithKeys(static function ($course) {
                return [$course->id => $course->code . ' - ' . $course->name];
            });

        return view('teachers.edit', [
            'colleges' => College::all()->pluck('name', 'id'),
            'courses' => $courses,
            'designations' => config('options.teachers.designations'),
            'programmes' => $programmes,
            'teacher' => $teacher,
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $validatedData = $request->validated();
        $profile = $request->user()->profile;

        $profile->update($validatedData);

        if ($request->has('teaching_details')) {
            $profile->teachingDetails()->createMany($request->getTeachingRecord());
        }

        if ($request->has('profile_picture')) {
            $profile->profilePicture()->create($request->getProfilePicture());
        }

        flash('Profile Updated Successfully!')->success();

        return redirect(route('teachers.profile'));
    }

    public function avatar()
    {
        $attachmentPicture = auth()->user()->profile->profilePicture;

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
