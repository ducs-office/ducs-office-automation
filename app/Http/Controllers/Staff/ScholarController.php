<?php

namespace App\Http\Controllers\Staff;

use App\Events\ScholarCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Scholar\StoreJournalPublication;
use App\Http\Requests\Staff\ReplaceScholarCosupervisorRequest;
use App\Http\Requests\Staff\StoreScholarRequest;
use App\Http\Requests\Staff\UpdateScholarRequest;
use App\Mail\FillAdvisoryCommitteeMail;
use App\Mail\UserRegisteredMail;
use App\Models\Cosupervisor;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Models\User;
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
            'supervisors' => User::where('is_supervisor', true)->get()->pluck('id', 'name'),
            'cosupervisors' => Cosupervisor::all()->pluck('id', 'person.name'),
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

    public function store(StoreScholarRequest $request)
    {
        $validData = $request->validated();

        $plainPassword = Str::random(8);

        $scholar = Scholar::create($validData + ['password' => bcrypt($plainPassword)]);
        $scholar->supervisors()->attach($request->supervisor_id);
        $scholar->cosupervisors()->attach($request->cosupervisor_id);

        event(new ScholarCreated($scholar, $plainPassword));

        flash('New scholar added succesfully!')->success();

        return redirect(route('staff.scholars.index'));
    }

    public function update(UpdateScholarRequest $request, Scholar $scholar)
    {
        $validData = $request->validated();

        $scholar->update($validData);

        if (
            $request->supervisor_id !== null &&
            (int) $request->supervisor_id !== (int) $scholar->currentSupervisor->id
        ) {
            $scholar->supervisors()->wherePivot('ended_on', null)->sync([
                $request->supervisor_id => ['started_on' => $scholar->currentSupervisor->pivot->started_on],
            ]);
        }

        if (
            $request->cosupervisor_id !== null &&
            (int) $request->cosupervisor_id !== (int) $scholar->currentCosupervisor->id
        ) {
            $scholar->cosupervisors()->wherePivot('ended_on', null)->sync([
                $request->cosupervisor_id => ['started_on' => $scholar->currentCosupervisor->pivot->started_on],
            ]);
        }

        flash('Scholar updated successfully!')->success();

        return redirect()->back();
    }

    public function destroy(Scholar $scholar)
    {
        $scholar->delete();

        flash('Scholar deleted successfully!')->success();

        return redirect(route('staff.scholars.index'));
    }

    public function replaceCosupervisor(Request $request, Scholar $scholar)
    {
        $supervisorsCosupervisorProfile = Cosupervisor::query()
            ->where('person_type', User::class)
            ->where('person_id', $scholar->currentSupervisor->id)
            ->first();

        $scholarCosupervisor = optional($scholar->currentCosupervisor);
        $conflicts = [
            $supervisorsCosupervisorProfile->id,
            $scholarCosupervisor->id,
        ];

        $request->validate([
            'cosupervisor_id' => ['required', 'integer', Rule::notIn($conflicts), 'exists:cosupervisors,id'],
        ]);

        if ($scholar->currentCosupervisor) {
            $scholar->currentCosupervisor->pivot
                ->update(['ended_on' => today()]);
        }

        $scholar->cosupervisors()->attach($request->cosupervisor_id);

        flash('Co-Supervisor replaced successfully!')->success();

        return back();
    }

    public function replaceSupervisor(Scholar $scholar, Request $request)
    {
        $request->validate([
            'supervisor_id' => [
                'required', Rule::exists('users', 'id')
                    ->whereNot('id', $scholar->currentSupervisor->id)
                    ->where('is_supervisor', true),
            ],
        ]);

        $scholar->currentSupervisor->pivot
            ->update(['ended_on' => today()]);

        $scholar->supervisors()->attach($request->supervisor_id);

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
