<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeScholarAdvisorsRequest;
use App\Models\Cosupervisor;
use App\Models\ExternalAuthority;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\AdvisoryCommitteeMember;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\ReservationCategory;
use App\Types\ScholarDocumentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        ]);
    }

    public function updateAdvisors(ChangeScholarAdvisorsRequest $request, Scholar $scholar)
    {
        $validData = $request->validated();

        $updatedAdvisors = collect($validData['advisors'])->map(function ($item) use ($scholar) {
            return [
                'advisor_type' => isset($item['user_id']) ? User::class : ExternalAuthority::class,
                'advisor_id' => $item['user_id'] ?? $item['external_id'] ?? ExternalAuthority::create($item)->id,
                'started_on' => $scholar->currentAdvisors->min('started_on') ?? $scholar->created_at,
            ];
        });

        $scholar->currentAdvisors()->delete();
        $scholar->currentAdvisors()->createMany($updatedAdvisors->toArray());

        flash('Advisors Updated SuccessFully!')->success();

        return back();
    }

    public function replaceAdvisors(ChangeScholarAdvisorsRequest $request, Scholar $scholar)
    {
        if ($scholar->currentAdvisors->count() == 0) {
            flash('There must be advisors already assigned to be replaced.')->warning();
            return redirect()->back();
        }

        $validData = $request->validated();

        $updatedAdvisors = collect($validData['advisors'])->map(function ($item) use ($scholar) {
            return [
                'advisor_type' => isset($item['user_id']) ? User::class : ExternalAuthority::class,
                'advisor_id' => $item['user_id'] ?? $item['external_id'] ?? ExternalAuthority::create($item)->id,
                'started_on' => today(),
            ];
        });

        $scholar->currentAdvisors()->update(['ended_on' => today()]);
        $scholar->currentAdvisors()->createMany($updatedAdvisors->toArray());

        flash('Advisors Updated SuccessFully!')->success();

        return back();
    }
}
