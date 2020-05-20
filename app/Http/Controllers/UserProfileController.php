<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\User;
use App\Types\TeacherStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show(Request $request, User $user)
    {
        $user->load([
            'teachingRecords',
            'college',
            'teachingDetails',
        ]);

        return view('users.profile', [
            'designations' => TeacherStatus::values(),
            'user' => $user,
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

        return redirect(route('profiles.show', $user));
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

    public function avatar(User $user)
    {
        if (Storage::exists($user->avatar_path)) {
            return Response::file(Storage::path($user->avatar_path));
        }

        $avatar = file_get_contents($user->getGravatarUrl());

        return Response::make($avatar, 200, [
            'Content-Type' => 'image/jpg',
        ]);
    }
}
