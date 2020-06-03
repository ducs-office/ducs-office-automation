<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\UpdateProfileRequest;
use App\Models\Cosupervisor;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\EducationInfo;
use App\Types\FundingType;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\ReservationCategory;
use App\Types\ScholarDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Scholar $scholar, Request $request)
    {
        $scholar = $scholar->load([
            'publications',
            'presentations.publication',
        ]);

        return view('scholars.profile', [
            'scholar' => $scholar,
            'admissionModes' => AdmissionMode::values(),
            'genders' => Gender::values(),
            'categories' => ReservationCategory::values(),
            'eventTypes' => PresentationEventType::values(),
            'documentTypes' => ScholarDocumentType::values(),
        ]);
    }

    public function update(Scholar $scholar, UpdateProfileRequest $request)
    {
        $data = $request->validated();

        $data['education_details'] = collect($request->education_details)
            ->map(function ($education) {
                ScholarEducationSubject::firstOrCreate(['name' => $education['subject']]);
                ScholarEducationDegree::firstOrCreate(['name' => $education['degree']]);
                ScholarEducationInstitute::firstOrCreate(['name' => $education['institute']]);
                return new EducationInfo($education);
            })->toArray();

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('/avatars/scholars');
        }

        $scholar->update($data);

        flash('Profile updated successfully!')->success();

        return redirect()->back();
    }

    public function avatar(Scholar $scholar)
    {
        $avatarPath = $scholar->avatar_path;

        if ($avatarPath && Storage::exists($avatarPath)) {
            return Response::file(Storage::path($avatarPath));
        }

        $gravatar_contents = file_get_contents($scholar->getAvatarUrl());

        return Response::make($gravatar_contents, 200, [
            'Content-Type' => 'image/jpg',
        ]);
    }
}
