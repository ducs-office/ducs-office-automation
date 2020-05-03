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
use App\Types\EducationInfo;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\ReservationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'subjects' => ScholarEducationSubject::all()->pluck('name')->toArray(),
            'degrees' => ScholarEducationDegree::all()->pluck('name')->toArray(),
            'institutes' => ScholarEducationInstitute::all()->pluck('name')->toArray(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $scholar = $request->user();

        DB::beginTransaction();

        $validData = $request->validated();
        $validData['education_details'] = collect($request->education_details)
            ->map(function ($education) {
                ScholarEducationSubject::firstOrCreate(['name' => $education['subject']]);
                ScholarEducationDegree::firstOrCreate(['name' => $education['degree']]);
                ScholarEducationInstitute::firstOrCreate(['name' => $education['institute']]);
                return new EducationInfo($education);
            })->toArray();

        $scholar->update($validData);

        if ($request->has('profile_picture')) {
            $scholar->profilePicture()->create([
                'original_name' => $validData['profile_picture']->getClientOriginalName(),
                'path' => $validData['profile_picture']->store('/scholar_attachments/profile_picture'),
            ]);
        }

        DB::commit();

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
