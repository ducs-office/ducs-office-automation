<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeScholarAdvisorsRequest;
use App\Http\Requests\Scholar\UpdateProfileRequest;
use App\Models\Cosupervisor;
use App\Models\PhdCourse;
use App\Models\Pivot\ScholarAdvisor;
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

class ScholarProfileController extends Controller
{
    public function show(Scholar $scholar, Request $request)
    {
        $scholar = $scholar->load([
            'publications',
            'presentations.publication',
        ]);

        $advisors = $scholar->advisors->groupBy(function ($advisor) {
            return $advisor->pivot->started_on->format('M d, Y');
        });

        return view('scholars.profile', [
            'user' => $request->user(),
            'scholar' => $scholar,
            'courses' => PhdCourse::whereNotIn('id', $scholar->courseworks()->allRelatedIds())->get(),
            'recommendations' => ProgressReportRecommendation::values(),
            'documentTypes' => ScholarDocumentType::values(),
            'genders' => Gender::values(),
            'categories' => ReservationCategory::values(),
            'admissionModes' => AdmissionMode::values(),
            'fundings' => FundingType::values(),
            'degrees' => ScholarEducationDegree::all(),
            'institutes' => ScholarEducationInstitute::all(),
            'subjects' => ScholarEducationSubject::all(),
            'supervisors' => $scholar->supervisors,
            'cosupervisors' => $scholar->cosupervisors,
            'advisors' => $advisors,
        ]);
    }

    public function update(Scholar $scholar, UpdateProfileRequest $request)
    {
        $this->authorize('updateProfile', $scholar);

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

    public function updateAdvisors(ChangeScholarAdvisorsRequest $request, Scholar $scholar)
    {
        $this->authorize('manageAdvisoryCommittee', $scholar);

        $startedDate = $scholar->currentAdvisors->min('pivot.started_on') ?? $scholar->created_at;

        $scholar->advisors()->detach($scholar->currentAdvisors);
        $scholar->advisors()->attach($request->advisors, [
            'started_on' => $startedDate,
        ]);

        flash('Advisors Updated SuccessFully!')->success();

        return back();
    }

    public function replaceAdvisors(ChangeScholarAdvisorsRequest $request, Scholar $scholar)
    {
        $this->authorize('manageAdvisoryCommittee', $scholar);

        if ($scholar->currentAdvisors->count() == 0) {
            flash('There must be advisors already assigned to be replaced.')->warning();
            return redirect()->back();
        }

        foreach ($scholar->currentAdvisors as $currentAdvisor) {
            $currentAdvisor->pivot->update(['ended_on' => today()]);
        }

        $newAdvisors = collect($request->advisors)
                ->mapWithKeys(function ($advisor) {
                    return [$advisor => ['started_on' => today()]];
                });

        $scholar->advisors()->attach($newAdvisors->toArray());

        flash('Advisors Updated SuccessFully!')->success();

        return back();
    }
}
