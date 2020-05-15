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
use App\Models\ExternalAuthority;
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
            'supervisors' => User::supervisors()->get()->pluck('id', 'name'),
            'cosupervisors' => User::cosupervisors()->get()->pluck('id', 'name')->merge(
                ExternalAuthority::cosupervisors()->get()->pluck('id', 'name')
            ),
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

        if ($request->hasAny(['cosupervisor_user_id', 'cosupervisor_external_id'])) {
            $scholar->cosupervisors()->create([
                'person_type' => $request->has('cosupervisor_user_id') ? User::class : ExternalAuthority::class,
                'person_id' => $request->cosupervisor_user_id ?? $request->cosupervisor_external_id,
            ]);
        }

        event(new ScholarCreated($scholar, $plainPassword));

        flash('New scholar added succesfully!')->success();

        return redirect(route('staff.scholars.index'));
    }

    public function update(UpdateScholarRequest $request, Scholar $scholar)
    {
        $validData = $request->validated();

        $scholar->update($validData);

        if ($request->has('supervisor_id')) {
            $scholar->supervisors()->wherePivot('ended_on', null)->sync([
                $request->supervisor_id => ['started_on' => $scholar->currentSupervisor->pivot->started_on],
            ]);
        }

        if ($request->hasAny(['cosupervisor_user_id', 'cosupervisor_external_id'])) {
            if (! $request->cosupervisor_user_id && ! $request->cosupervisor_external_id) {
                $scholar->currentCosupervisor()->delete();
            } else {
                $scholar->cosupervisors()->updateOrCreate(['ended_on' => null], [
                    'person_type' => $request->has('cosupervisor_user_id') ? User::class : ExternalAuthority::class,
                    'person_id' => $request->cosupervisor_user_id ?? $request->cosupervisor_external_id,
                ]);
            }
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
        $userConflicts = [$scholar->currentSupervisor->id];
        $externalConflicts = [];

        if ($cosupervisor = $scholar->currentCosupervisor) {
            if ($cosupervisor->person_type === User::class) {
                $userConflicts[] = $scholar->currentCosupervisor->person_id;
            } else {
                $externalConflicts[] = $scholar->currentCosupervisor->person_id;
            }
        }

        $request->validate([
            'user_id' => [
                'nullable', 'integer',
                Rule::notIn($userConflicts),
                Rule::exists(User::class, 'id')
                    ->where(function ($q) {
                        return $q->where('is_supervisor', true)
                            ->orWhere('is_cosupervisor', true);
                    }),
            ],
            'external_id' => [
                'nullable', 'integer', Rule::notIn($externalConflicts), 'exists:external_authorities,id',
            ],
        ]);

        if ($scholar->currentCosupervisor) {
            $scholar->currentCosupervisor->update(['ended_on' => today()]);
        }

        if ($request->user_id || $request->external_id) {
            $scholar->cosupervisors()->create([
                'person_type' => $request->has('user_id') ? User::class : ExternalAuthority::class,
                'person_id' => $request->user_id ?? $request->external_id,
            ]);
        }

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
