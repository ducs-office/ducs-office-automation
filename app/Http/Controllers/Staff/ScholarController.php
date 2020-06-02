<?php

namespace App\Http\Controllers\Staff;

use App\Events\ScholarCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreScholarRequest;
use App\Http\Requests\Staff\UpdateScholarRequest;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ScholarController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Scholar::class);

        $cosupervisors = User::select(['id', 'first_name', 'last_name', 'is_supervisor', 'is_cosupervisor'])
            ->allCosupervisors()->get();

        $scholars = Scholar::query();

        if (! $request->user()->can('scholars:view') && $request->user()->isSupervisor()) {
            $scholars->whereHas('supervisors', function ($query) use ($request) {
                $query->where('ended_on', null)
                    ->where('users.id', $request->user()->id);
            });
        }

        return view('staff.scholars.index', [
            'scholars' => $scholars->get(),
            'supervisors' => $cosupervisors->where('is_supervisor', true)->pluck('name', 'id'),
            'cosupervisors' => $cosupervisors->pluck('name', 'id'),
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

        if ($request->has('cosupervisor_id')) {
            $scholar->cosupervisors()->attach($request->cosupervisor_id);
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

        if ($request->cosupervisor_id != null) {
            $scholar->cosupervisors()->wherePivot('ended_on', null)->sync([
                $request->cosupervisor_id => [
                    'started_on' => $scholar->currentCosupervisor
                        ? $scholar->currentCosupervisor->pivot->started_on
                        : today(),
                ],
            ]);
        } else {
            // dd($request->cosupervisor_id);
            // TODO: Write a test for this.
            // changing cosupervisor to null without tracking change.
            $scholar->cosupervisors()->wherePivot('ended_on', null)->sync([]);
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
        $conflicts = [
            $scholar->currentSupervisor->id,
            optional($scholar->currentCosupervisor)->id,
        ];

        $request->validate([
            'cosupervisor_id' => [
                'nullable', 'integer', Rule::notIn($conflicts),
                Rule::exists(User::class, 'id')
                    ->where(function ($q) {
                        return $q->where('is_supervisor', true)
                            ->orWhere('is_cosupervisor', true);
                    }),
            ],
        ]);

        if ($scholar->currentCosupervisor) {
            $scholar->currentCosupervisor->pivot->update(['ended_on' => today()]);
        }

        if ($request->cosupervisor_id) {
            $scholar->cosupervisors()->attach($request->cosupervisor_id);
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
}
