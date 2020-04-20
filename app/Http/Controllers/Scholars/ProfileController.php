<?php

namespace App\Http\Controllers\Scholars;

use App\Cosupervisor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\UpdateProfileRequest;
use App\ScholarEducationSubject;
use App\SupervisorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $scholar = $request->user()->load([
            'publications',
            'presentations.publication',
        ]);

        return view('scholars.profile', [
            'scholar' => $scholar,
            'admissionCriterias' => config('options.scholars.admission_criterias'),
            'genders' => config('options.scholars.genders'),
            'categories' => config('options.scholars.categories'),
            'eventTypes' => config('options.scholars.academic_details.event_types'),
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
            'subjects' => ScholarEducationSubject::all()->pluck('id', 'name')->toArray(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $scholar = $request->user();
        $rules = $request->rules();

        foreach ($request->education as $index => $edu) {
            if ($edu['subject'] == -1 ) {
                $rules['subject.' . $index] = ['required', 'bail', 'string', 'min:5'];
            }
        }

        $validData = $request->validate($rules);

        foreach ($request->education as $index => $edu) {
            if ($edu['subject'] == -1) {
                $id = ScholarEducationSubject::firstOrCreate(
                    ['name' => $request->subject[$index]]
                )->id;
                $validData['education'][$index]['subject'] = $id;
            }
        }

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
