<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\UpdateProfileRequest;
use App\Models\Cosupervisor;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\SupervisorProfile;
use App\Types\AdmissionMode;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\ReservationCategory;
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
            'admissionModes' => AdmissionMode::values(),
            'genders' => Gender::values(),
            'categories' => ReservationCategory::values(),
            'eventTypes' => PresentationEventType::values(),
        ]);
    }

    public function edit(Request $request)
    {
        $scholar = $request->user();

        return view('scholars.edit', [
            'scholar' => $scholar,
            'categories' => ReservationCategory::values(),
            'admissionModes' => AdmissionMode::values(),
            'genders' => Gender::values(),
            'supervisorProfiles' => SupervisorProfile::all()->pluck('id', 'supervisor.name'),
            'cosupervisors' => Cosupervisor::all()->pluck('id', 'name', 'email'),
            'degrees' => ScholarEducationDegree::select(['id', 'name'])->get(),
            'institutes' => ScholarEducationInstitute::select(['id', 'name'])->get(),
            'subjects' => ScholarEducationSubject::select(['id', 'name'])->get(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $scholar = $request->user();
        $rules = $request->rules();

        foreach ($request->education as $index => $education) {
            if ($education['subject'] == -1) {
                $rules['typedSubjects.' . $index] = ['required', 'bail', 'string', 'min:5'];
            }
            if ($education['degree'] == -1) {
                $rules['typedDegrees.' . $index] = ['required', 'bail', 'string', 'min:5'];
            }
            if ($education['institute'] == -1) {
                $rules['typedInstitutes.' . $index] = ['required', 'bail', 'string', 'min:5'];
            }
        }

        $validData = $request->validate($rules);

        foreach ($request->education as $index => $education) {
            if ($education['subject'] == -1) {
                $id = ScholarEducationSubject::firstOrCreate(
                    ['name' => $request->typedSubjects[$index]]
                )->id;
                $validData['education'][$index]['subject'] = $id;
            }

            if ($education['degree'] == -1) {
                $id = ScholarEducationDegree::firstOrCreate(
                    ['name' => $request->typedDegrees[$index]]
                )->id;
                $validData['education'][$index]['degree'] = $id;
            }

            if ($education['institute'] == -1) {
                $id = ScholarEducationInstitute::firstOrCreate(
                    ['name' => $request->typedInstitutes[$index]]
                )->id;
                $validData['education'][$index]['institute'] = $id;
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
