<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\User;
use App\Types\Designation;
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
            'teacherStatus' => TeacherStatus::values(),
            'designations' => Designation::values(),
            'user' => $user,
        ]);
    }

    public function update(UserProfileUpdateRequest $request, User $user)
    {
        $user->update($request->validated() + [
            'avatar_path' => $request->uploadAvatar() ?? $user->avatar_path,
        ]);

        flash('Profile Updated Successfully!')->success();

        return redirect(route('profiles.show', $user));
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
