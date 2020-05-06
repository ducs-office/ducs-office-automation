<?php

namespace App\Http\Controllers\Staff;

use App\Events\ScholarCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StoreJournalPublication;
use App\Mail\FillAdvisoryCommitteeMail;
use App\Mail\UserRegisteredMail;
use App\Models\Cosupervisor;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response as Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ScholarController extends Controller
{
    public function index()
    {
        return view('staff.scholars.index', [
            'scholars' => Scholar::all(),
            'supervisors' => SupervisorProfile::all()->pluck('id', 'supervisor.name'),
            'cosupervisors' => Cosupervisor::all()->pluck('id', 'name', 'email'),
        ]);
    }

    public function avatar(Scholar $scholar)
    {
        $attachmentPicture = $scholar->profile->profilePicture;

        if ($attachmentPicture && Storage::exists($attachmentPicture->path)) {
            return Response::file(Storage::path($attachmentPicture->path));
        }

        $gravatarHash = md5(strtolower(trim($scholar->email)));
        $avatar = file_get_contents('https://gravatar.com/avatar/' . $gravatarHash . '?s=200&d=identicon');

        return Response::make($avatar, 200, [
            'Content-Type' => 'image/jpg',
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validCosupervisorTypes = 'App\Models\Cosupervisor, App\Models\SupervisorProfile';

        $validData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:scholars',
            'supervisor_profile_id' => 'required| exists:supervisor_profiles,id',
            'cosupervisor_profile_id' => 'nullable| integer',
            'cosupervisor_profile_type' => 'nullable| in:' . $validCosupervisorTypes,
        ]);

        $plainPassword = Str::random(8);

        $scholar = Scholar::create($validData + ['password' => bcrypt($plainPassword)]);

        event(new ScholarCreated($scholar, $plainPassword));

        flash('New scholar added succesfully!')->success();

        return redirect(route('staff.scholars.index'));
    }

    public function update(Request $request, Scholar $scholar)
    {
        $validCosupervisorTypes = 'App\Models\Cosupervisor, App\Models\SupervisorProfile';

        $validData = $request->validate([
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'email' => 'sometimes|required|' . Rule::unique('scholars')->ignore($scholar),
            'supervisor_profile_id' => 'sometimes|required|exists:supervisor_profiles,id',
            'cosupervisor_profile_id' => 'nullable| integer',
            'cosupervisor_profile_type' => 'nullable| in:' . $validCosupervisorTypes,
        ]);

        $scholar->update($validData);

        flash('Scholar updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(Scholar $scholar)
    {
        $scholar->delete();

        flash('Scholar deleted successfully!')->success();

        return redirect(route('staff.scholars.index'));
    }

    public function replaceCosupervisor(Scholar $scholar, Request $request)
    {
        // dd($request->input('cosupervisor_profile_type'));
        $cosupervisorProfileTypes = [
            'App\Models\Cosupervisor' => 'cosupervisors',
            'App\Models\SupervisorProfile' => 'supervisor_profiles',
        ];

        // $value = ($scholar->cosupervisor) ? $scholar->cosupervisor_profile_id : null;

        $validCosupervisor = $request->validate([
            'cosupervisor_profile_type' => 'nullable|in:' . implode(',', array_keys($cosupervisorProfileTypes)),
            'cosupervisor_profile_id' => 'nullable| integer',
            //  |exists:'.$cosupervisorProfileTypes[$request->input('cosupervisor_profile_type')].',id'
        ]);
        // not In:' . $value . '|' .
        // Rule::requiredIf(function () use ($scholar) {
        //     return ! $scholar->cosupervisor;
        // })

        $oldCosupervisor = $scholar->cosupervisor;

        $oldCosupervisors = $scholar->old_cosupervisors;

        array_push($oldCosupervisors, [
            'name' => ($oldCosupervisor) ? $oldCosupervisor->name : null,
            'email' => ($oldCosupervisor) ? $oldCosupervisor->email : null,
            'designation' => ($oldCosupervisor) ? $oldCosupervisor->designation : null,
            'affiliation' => ($oldCosupervisor) ? $oldCosupervisor->affiliation : null,
            'date' => now()->format('d F Y'),
        ]);

        $this->rememberOldAdvisoryCommittee($scholar);

        $scholar->update($validCosupervisor + ['old_cosupervisors' => $oldCosupervisors]);

        flash('Co-Supervisor replaced successfully!')->success();

        return back();
    }

    public function replaceSupervisor(Scholar $scholar, Request $request)
    {
        $validSupervisorProfileId = $request->validate([
            'supervisor_profile_id' => 'required|exists:supervisor_profiles,id|not In:' . $scholar->supervisor_profile_id,
        ]);

        $oldSupervisor = $scholar->supervisor;
        $oldSupervisors = $scholar->old_supervisors;

        array_push($oldSupervisors, [
            'name' => $oldSupervisor->name,
            'email' => $oldSupervisor->email,
            'date' => now()->format('d F Y'),
        ]);

        $this->rememberOldAdvisoryCommittee($scholar);

        $scholar->update($validSupervisorProfileId + ['old_supervisors' => $oldSupervisors]);

        flash('Supervisor replaced successfully!')->success();

        return back();
    }

    public function rememberOldAdvisoryCommittee(Scholar $scholar)
    {
        $oldAdvisoryCommittees = $scholar->old_advisory_committees;

        $currentAdvisoryCommittee = [
            'committee' => $scholar->advisory_committee,
            'to_date' => today(),
            'from_date' => count($oldAdvisoryCommittees) > 0 ?
                $oldAdvisoryCommittees[count($oldAdvisoryCommittees) - 1]['to_date'] :
                $scholar->created_at,
        ];

        array_unshift($oldAdvisoryCommittees, $currentAdvisoryCommittee);

        $scholar->update(['old_advisory_committees' => $oldAdvisoryCommittees]);
    }
}
