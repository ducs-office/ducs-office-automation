<?php

namespace App\Http\Controllers\Scholars;

use App\Cosupervisor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\UpdateProfileRequest;
use App\ScholarEducationDegree;
use App\ScholarEducationInstitute;
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

        $education = $this->getScholarEducationNames($scholar->education);

        return view('scholars.profile', [
            'scholar' => $scholar,
            'admissionCriterias' => config('options.scholars.admission_criterias'),
            'genders' => config('options.scholars.genders'),
            'categories' => config('options.scholars.categories'),
            'eventTypes' => config('options.scholars.academic_details.event_types'),
            'education' => $education,
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
            'degrees' => ScholarEducationDegree::all()->pluck('id', 'name')->toArray(),
            'institutes' => ScholarEducationInstitute::all()->pluck('id', 'name')->toArray(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $scholar = $request->user();
        $rules = $request->rules();

        foreach ($request->education as $index => $edu) {
            if ($edu['subject'] == -1) {
                $rules['typedSubjects.' . $index] = ['required', 'bail', 'string', 'min:5'];
            }
            if ($edu['degree'] == -1) {
                $rules['typedDegrees.' . $index] = ['required', 'bail', 'string', 'min:5'];
            }
            if ($edu['institute'] == -1) {
                $rules['typedInstitutes.' . $index] = ['required', 'bail', 'string', 'min:5'];
            }
        }

        $validData = $request->validate($rules);

        foreach ($request->education as $index => $edu) {
            if ($edu['subject'] == -1) {
                $id = ScholarEducationSubject::firstOrCreate(
                    ['name' => $request->typedSubjects[$index]]
                )->id;
                $validData['education'][$index]['subject'] = $id;
            }

            if ($edu['degree'] == -1) {
                $id = ScholarEducationDegree::firstOrCreate(
                    ['name' => $request->typedDegrees[$index]]
                )->id;
                $validData['education'][$index]['degree'] = $id;
            }

            if ($edu['institute'] == -1) {
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

    public function getScholarEducationNames($scholarEducation)
    {
        $education = [];

        foreach ($scholarEducation as $edu) {
            $subject = ScholarEducationSubject::find($edu['subject'])->name;
            $degree = ScholarEducationDegree::find($edu['degree'])->name;
            $institute = ScholarEducationInstitute::find($edu['institute'])->name;
            $year = $edu['year'];

            array_push($education, [
                'subject' => $subject,
                'degree' => $degree,
                'institute' => $institute,
                'year' => $year,
            ]);
        }

        return $education;
    }
}
