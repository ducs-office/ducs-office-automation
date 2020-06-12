<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\UpdateProfileRequest;
use App\Models\Cosupervisor;
use App\Models\PhdCourse;
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
use App\Types\ProgressReportRecommendation;
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

        $courses = PhdCourse::all()->filter(function ($value) use ($scholar) {
            return ! $scholar->courseworks->map->pivot->map->phd_course_id->contains($value->id);
        });

        return view('scholars.profile', [
            'scholar' => $scholar,
            'courses' => $courses,
            'eventTypes' => PresentationEventType::values(),
            'documentTypes' => ScholarDocumentType::values(),
            'recommendations' => ProgressReportRecommendation::values(),
            'degrees' => ScholarEducationDegree::all(),
            'institutes' => ScholarEducationInstitute::all(),
            'subjects' => ScholarEducationSubject::all(),
        ]);
    }

    public function update(Scholar $scholar, UpdateProfileRequest $request)
    {
        $this->authorize('updateProfile', [Scholar::class, $scholar]);

        $data = $request->validated();

        if ($request->has('education_details')) {
            $data['education_details'] = $request->getEducationDetails();
        }

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

        $gravatar_contents = file_get_contents($scholar->avatar_url);

        return Response::make($gravatar_contents, 200, [
            'Content-Type' => 'image/jpg',
        ]);
    }
}
