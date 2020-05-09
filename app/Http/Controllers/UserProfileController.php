<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdateProfileRequest;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\College;
use App\Models\Programme;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Types\TeacherStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
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
            'designations' => TeacherStatus::values(),
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

        $programmes = Programme::withLatestRevision()->get();

        return view('teachers.edit', [
            'colleges' => College::all()->pluck('name', 'id'),
            'designations' => TeacherStatus::values(),
            'programmes' => $programmes,
            'teacher' => $teacher,
        ]);
    }

    public function update(UserProfileUpdateRequest $request, User $user)
    {
        $user = $user->load('teachingDetails');

        $user->update($request->validated() + [
            'avatar_path' => $request->uploadAvatar() ?? $user->avatar_path,
        ]);

        $this->syncTeachingDetails($user, $request->getTeachingRecord());

        flash('Profile Updated Successfully!')->success();

        return redirect(route('teachers.profile'));
    }

    protected function syncTeachingDetails(User $user, array $records)
    {
        $currentDetails = $user->teachingDetails->map->only([
            'programme_revision_id',
            'course_id',
            'semester',
        ])->mapWithKeys(function (array $detail) {
            return [implode(',', $detail) => collect($detail)];
        });

        $newDetails = collect($records)->mapWithKeys(function (array $detail) {
            return [implode(',', $detail) => collect($detail)];
        });

        $toBeCreated = $newDetails->diffKeys($currentDetails)->toArray();
        $toBeDeleted = $currentDetails->diffKeys($newDetails);

        $user->teachingDetails()->createMany($toBeCreated);
        $user->teachingDetails()
            ->whereIn('programme_revision_id', $toBeDeleted->pluck('programme_revision_id')->toArray())
            ->whereIn('course_id', $toBeDeleted->pluck('course_id')->toArray())
            ->whereIn('semester', $toBeDeleted->pluck('semester')->toArray())
            ->delete();
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
