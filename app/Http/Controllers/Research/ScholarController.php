<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Models\Cosupervisor;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Http\Request;

class ScholarController extends Controller
{
    public function __construct()
    {
        return $this->authorizeResource(Scholar::class, 'scholar');
    }

    public function index(Request $request)
    {
        $profile = $request->user()->supervisorProfile;

        if (! $profile) {
            $scholars = Scholar::all();
        } else {
            $scholars = $profile->scholars;
        }

        return view('research.scholars.index', [
            'scholars' => $scholars,
        ]);
    }

    public function show(Scholar $scholar)
    {
        return view('research.scholars.show', [
            'scholar' => $scholar->load(['courseworks']),
            'categories' => config('options.scholars.categories'),
            'admissionCriterias' => config('options.scholars.admission_criterias'),
            'courses' => PhdCourse::whereNotIn('id', $scholar->courseworks()->allRelatedIds())->get(),
            'genders' => config('options.scholars.genders'),
            'eventTypes' => config('options.scholars.academic_details.event_types'),
            'faculty' => User::where('category', 'faculty_teacher')->get(),
        ]);
    }

    public function updateAdvisoryCommittee(Request $request, Scholar $scholar)
    {
        $validData = $request->validate([
            'faculty_teacher' => ['required', 'string'],
            'external' => ['required', 'array', 'size: 5'],
            'external.name' => ['required', 'string'],
            'external.designation' => ['required', 'string'],
            'external.affiliation' => ['required', 'string'],
            'external.email' => ['required', 'email'],
            'external.phone_no' => ['nullable', 'string'],
        ]);

        $scholar->update(['advisory_committee' => $validData]);

        flash("Scholar's Advisory Committee Updated SuccessFully!")->success();

        return back();
    }

    public function replaceAdvisoryCommittee(Request $request, Scholar $scholar)
    {
        $validData = $request->validate([
            'faculty_teacher' => ['required', 'string'],
            'external' => ['required', 'array', 'size: 5'],
            'external.name' => ['required', 'string'],
            'external.designation' => ['required', 'string'],
            'external.affiliation' => ['required', 'string'],
            'external.email' => ['required', 'email'],
            'external.phone_no' => ['nullable', 'string'],
        ]);

        $currentAdvisoryCommittee = array_merge(
            $scholar->advisory_committee,
            ['date' => now()->format('d F Y')]
        );

        $oldAdvisoryCommittees = $scholar->old_advisory_committees;

        array_unshift($oldAdvisoryCommittees, $currentAdvisoryCommittee);

        $scholar->update([
            'advisory_committee' => $validData,
            'old_advisory_committees' => $oldAdvisoryCommittees,
        ]);

        flash("Scholar's Advisory Committee Replaced SuccessFully!")->success();

        return back();
    }
}
