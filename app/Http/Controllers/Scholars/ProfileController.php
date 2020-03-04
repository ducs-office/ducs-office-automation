<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\SupervisorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $scholar = $request->user()->load(['profile']);

        return view('scholars.profile', [
            'scholar' => $scholar,
            'categories' => config('options.scholars.categories'),
            'admission_criterias' => config('options.scholars.admission_criterias'),
            'supervisors' => SupervisorProfile::all()->pluck('id', 'supervisor.name'),
        ]);
    }

    public function edit(Request $request)
    {
        $scholar = $request->user()->load(['profile']);

        return view('scholars.edit', [
            'scholar' => $scholar,
            'categories' => config('options.scholars.categories'),
            'admission_criterias' => config('options.scholars.admission_criterias'),
        ]);
    }

    public function update(Request $request)
    {
        $scholar = Auth::user();

        $validData = $request->validate([
            'phone_no' => [Rule::requiredIf($scholar->profile->phone_no != null)],
            'address' => [Rule::requiredIf($scholar->profile->address != null)],
            'category' => [Rule::requiredIf($scholar->profile->category != null)],
            'admission_via' => [Rule::requiredIf($scholar->profile->admission_via != null)],
            'profile_picture' => ['nullable', 'image'],
            'advisors' => ['nullable', 'array'],
            'advisors.advisory_committee.*' => ['nullable', 'array', 'size: 4'],
            'advisors.co_supervisors.*' => ['nullable', 'array', 'size: 4'],
        ]);

        $scholar->profile->update($validData);

        $scholar->profile->advisors->each->delete();

        $scholar->profile->advisors()->createMany(
            array_map(function ($advisorCommitteeMember) {
                return array_merge($advisorCommitteeMember, ['type' => 'A']);
            }, $validData['advisors']['advisory_committee'])
        );

        $scholar->profile->advisors()->createMany(
            array_map(function ($advisorCommitteeMember) {
                return array_merge($advisorCommitteeMember, ['type' => 'C']);
            }, $validData['advisors']['co_supervisors'])
        );

        if ($request->has('profile_picture')) {
            $scholar->profile->profilePicture()->create([
                'original_name' => $validData['profile_picture']->getClientOriginalName(),
                'path' => $validData['profile_picture']->store('/scholar_attachments/profile_picture'),
            ]);
        }
        flash('Profile updated successfully!')->success();

        return redirect(route('scholars.profile'));
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
            'Content-type' => 'image/jpg',
        ]);
    }
}
