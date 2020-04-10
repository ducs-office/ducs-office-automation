<?php

namespace App\Http\Controllers\Research;

use App\Cosupervisor;
use App\Http\Controllers\Controller;
use App\PhdCourse;
use App\Scholar;
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

        return view('research.scholars.index', ['scholars' => $scholars]);
    }

    public function show(Scholar $scholar)
    {
        return view('research.scholars.show', [
            'scholar' => $scholar->load(['courseworks', 'cosupervisors']),
            'categories' => config('options.scholars.categories'),
            'admissionCriterias' => config('options.scholars.admission_criterias'),
            'courses' => PhdCourse::whereNotIn('id', $scholar->courseworks()->allRelatedIds())->get(),
            'genders' => config('options.scholars.genders'),
        ]);
    }
}
