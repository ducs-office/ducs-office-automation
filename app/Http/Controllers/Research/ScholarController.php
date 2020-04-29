<?php

namespace App\Http\Controllers\Research;

use App\Casts\AdvisoryCommittee;
use App\Http\Controllers\Controller;
use App\Models\Cosupervisor;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\User;
use App\Types\AdvisoryCommitteeMember;
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
            'committee' => ['required', 'array', 'max:5'],
            'committee.*.type' => ['required', 'string', 'in:faculty_teacher,external'],
            'committee.*.id' => ['required_if:commmitte.*.type,faculty_teacher', 'integer'],
            'committee.*.name' => ['required_if:commmitte.*.type,external', 'string'],
            'committee.*.designation' => ['required_if:commmitte.*.type,external', 'string'],
            'committee.*.affiliation' => ['required_if:commmitte.*.type,external', 'string'],
            'committee.*.email' => ['required_if:commmitte.*.type,external', 'email'],
            'committee.*.phone' => ['nullable', 'string'],
        ]);

        $committee = collect($validData['committee'])->map(function ($item) {
            if ($item['type'] == 'faculty_teacher') {
                return AdvisoryCommitteeMember::fromFacultyTeacher(User::find($item['id']));
            }
            return new AdvisoryCommitteeMember($item['type'], $item);
        })->toArray();

        $scholar->update(['advisory_committee' => $committee]);

        flash("Scholar's Advisory Committee Updated SuccessFully!")->success();

        return back();
    }

    public function replaceAdvisoryCommittee(Request $request, Scholar $scholar)
    {
        $validData = $request->validate([
            'committee' => ['required', 'array', 'max:5'],
            'committee.*.type' => ['required', 'string', 'in:faculty_teacher,external'],
            'committee.*.id' => ['required_if:commmitte.*.type,faculty_teacher', 'integer'],
            'committee.*.name' => ['required_if:commmitte.*.type,external', 'string'],
            'committee.*.designation' => ['required_if:commmitte.*.type,external', 'string'],
            'committee.*.affiliation' => ['required_if:commmitte.*.type,external', 'string'],
            'committee.*.email' => ['required_if:commmitte.*.type,external', 'email'],
            'committee.*.phone' => ['nullable', 'string'],
        ]);

        $oldAdvisoryCommittees = $scholar->old_advisory_committees;

        $currentAdvisoryCommittee = [
            'committee' => $scholar->advisory_committee,
            'to_date' => today(),
            'from_date' => count($oldAdvisoryCommittees) > 0 ?
                $oldAdvisoryCommittees[count($oldAdvisoryCommittees) - 1]['to_date'] :
                $scholar->created_at,
        ];

        array_unshift($oldAdvisoryCommittees, $currentAdvisoryCommittee);

        $newCommittee = collect($validData['committee'])->map(function ($item) {
            if ($item['type'] == 'faculty_teacher') {
                return AdvisoryCommitteeMember::fromFacultyTeacher(User::find($item['id']));
            }
            return new AdvisoryCommitteeMember($item['type'], $item);
        })->toArray();

        $scholar->update([
            'advisory_committee' => $newCommittee,
            'old_advisory_committees' => $oldAdvisoryCommittees,
        ]);

        flash("Scholar's Advisory Committee Replaced SuccessFully!")->success();

        return back();
    }
}
