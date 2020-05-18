<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeScholarAdvisorsRequest;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\ReservationCategory;
use Illuminate\Http\Request;

class ScholarController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Scholar::class, 'scholar');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->isSupervisor()) {
            $scholars = Scholar::all();
        } else {
            $scholars = $user->scholars;
        }

        return view('research.scholars.index', [
            'scholars' => $scholars,
        ]);
    }

    public function show(Scholar $scholar)
    {
        $existingSupervisors = User::query()
            ->select('id', 'first_name', 'last_name')
            ->supervisors()->get()
            ->pluck('name', 'id')
            ->forget($scholar->currentSupervisor->id);

        return view('research.scholars.show', [
            'scholar' => $scholar->load(['courseworks', 'progressReports', 'documents', 'publications']),
            'courses' => PhdCourse::whereNotIn('id', $scholar->courseworks()->allRelatedIds())->get(),
            'categories' => ReservationCategory::values(),
            'admissionModes' => AdmissionMode::values(),
            'genders' => Gender::values(),
            'eventTypes' => PresentationEventType::values(),
            'documentTypes' => ScholarDocumentType::values(),
        ]);
    }

    public function updateAdvisors(ChangeScholarAdvisorsRequest $request, Scholar $scholar)
    {
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
        if ($scholar->currentAdvisors->count() == 0) {
            flash('There must be advisors already assigned to be replaced.')->warning();
            return redirect()->back();
        }

        $newAdvisors = collect($request->advisors)
                ->mapWithKeys(function ($advisor) {
                    return [$advisor => ['started_on' => today()]];
                });

        $updatedAdvisors = $scholar->currentAdvisors
            ->mapWithKeys(function ($advisor) {
                return [$advisor->id => ['ended_on' => today()]];
            })
            ->concat($newAdvisors)->toArray();

        $scholar->advisors()->syncWithoutDetaching($updatedAdvisors);

        flash('Advisors Updated SuccessFully!')->success();

        return back();
    }
}
