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
        $scholar = $request->user()->load(['presentations', 'publications']);

        return view('scholars.profile', [
            'scholar' => $scholar,
            'admissionCriterias' => config('options.scholars.admission_criterias'),
            'genders' => config('options.scholars.genders'),
            'categories' => config('options.scholars.categories'),
        ]);
    }

    public function edit(Request $request)
    {
        $scholar = $request->user();

        return view('scholars.edit', [
            'scholar' => $scholar,
            'categories' => config('options.scholars.categories'),
            'admissionCriterias' => config('options.scholars.admission_criterias'),
            'genders' => config('options.scholars.genders'),
            'supervisorProfiles' => SupervisorProfile::all()->pluck('id', 'supervisor.name'),
        ]);
    }

    public function update(Request $request)
    {
        $scholar = Auth::user();

        $validData = $request->validate([
            'phone_no' => [Rule::requiredIf($scholar->phone_no != null)],
            'address' => [Rule::requiredIf($scholar->address != null)],
            'category' => [Rule::requiredIf($scholar->category != null)],
            'admission_via' => [Rule::requiredIf($scholar->admission_via != null)],
            'profile_picture' => ['nullable', 'image'],
            'research_area' => [Rule::requiredIf($scholar->research_area != null)],
            'supervisor_profile_id' => ['exists:supervisor_profiles,id'],
            'enrollment_date' => ['date', 'before:today'],
            'advisory_committee' => ['nullable', 'array', 'max: 4'],
            'co_supervisors' => ['nullable', 'array', 'max:2'],
        ]);

        $scholar->update($validData);

        if ($request->has('profile_picture')) {
            $scholar->profilePicture()->create([
                'original_name' => $validData['profile_picture']->getClientOriginalName(),
                'path' => $validData['profile_picture']->store('/scholar_attachments/profile_picture'),
            ]);
        }
        flash('Profile updated successfully!')->success();

        return redirect(route('scholars.profile'));
    }

    public function avatar()
    {
        $attachmentPicture = auth()->user()->profilePicture;

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
